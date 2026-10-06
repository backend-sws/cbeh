<?php

namespace App\Services;

use App\Models\ChargeCategory;
use App\Models\ChargeType;
use App\Models\HospitalCharge;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class HospitalChargeImportService
{
    /**
     * Import hospital charges from an uploaded or local file (.xlsx or .csv).
     *
     * @param string $filePath Full path to the file
     * @param string|null $originalExtension Optional original extension (e.g. from uploaded file)
     * @return array Result summary: [success, total, imported, updated, skipped, errors, message]
     */
    public function import(string $filePath, ?string $originalExtension = null): array
    {
        if (!file_exists($filePath)) {
            return [
                'success' => false,
                'message' => "File not found at: {$filePath}",
                'total' => 0,
                'imported' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => ["File not found at: {$filePath}"],
            ];
        }

        $extension = $originalExtension ? strtolower(ltrim($originalExtension, '.')) : strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // Auto-detect magic bytes for tmp files or unknown extensions
        if ($extension === 'tmp' || empty($extension) || !in_array($extension, ['xlsx', 'csv', 'txt'])) {
            $handle = @fopen($filePath, 'rb');
            if ($handle) {
                $header = fread($handle, 4);
                fclose($handle);
                // XLSX files are ZIP archives starting with PK (0x50 0x4B 0x03 0x04)
                if ($header === "PK\x03\x04") {
                    $extension = 'xlsx';
                } else {
                    $extension = 'csv';
                }
            } else {
                $extension = 'xlsx';
            }
        }

        if ($extension === 'xlsx') {
            $rows = $this->parseXlsx($filePath);
        } elseif (in_array($extension, ['csv', 'txt'])) {
            $rows = $this->parseCsv($filePath);
        } else {
            return [
                'success' => false,
                'message' => "Unsupported file format (.{$extension}). Please upload .xlsx or .csv file.",
                'total' => 0,
                'imported' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => ["Unsupported file format (.{$extension})"],
            ];
        }

        if (empty($rows)) {
            return [
                'success' => false,
                'message' => "No valid data rows found in the file.",
                'total' => 0,
                'imported' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => ["File is empty or could not be parsed"],
            ];
        }

        return $this->processRows($rows);
    }

    /**
     * Parse .xlsx file into structured rows using native ZipArchive + SimpleXML.
     */
    public function parseXlsx(string $filePath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            Log::error("HospitalChargeImportService: Failed to open zip archive for XLSX: {$filePath}");
            return [];
        }

        // 1. Read shared strings
        $sharedStrings = [];
        if (($idx = $zip->locateName('xl/sharedStrings.xml')) !== false) {
            $xmlContent = $zip->getFromIndex($idx);
            if ($xmlContent !== false) {
                $xml = simplexml_load_string($xmlContent);
                if ($xml && isset($xml->si)) {
                    foreach ($xml->si as $si) {
                        if (isset($si->t)) {
                            $sharedStrings[] = (string)$si->t;
                        } elseif (isset($si->r)) {
                            $text = '';
                            foreach ($si->r as $r) {
                                $text .= (string)$r->t;
                            }
                            $sharedStrings[] = $text;
                        } else {
                            $sharedStrings[] = '';
                        }
                    }
                }
            }
        }

        // 2. Read first sheet
        $sheetXmlContent = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($sheetXmlContent === false) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                if (preg_match('#xl/worksheets/sheet\d+\.xml#', $stat['name'])) {
                    $sheetXmlContent = $zip->getFromIndex($i);
                    break;
                }
            }
        }

        $zip->close();

        if ($sheetXmlContent === false) {
            Log::error("HospitalChargeImportService: No worksheet found in XLSX: {$filePath}");
            return [];
        }

        $sheetXml = simplexml_load_string($sheetXmlContent);
        if (!$sheetXml || !isset($sheetXml->sheetData->row)) {
            return [];
        }

        $rows = [];
        foreach ($sheetXml->sheetData->row as $r) {
            $row = [];
            foreach ($r->c as $c) {
                $val = (string)$c->v;
                $type = (string)$c['t'];

                if ($type === 's') {
                    $val = $sharedStrings[(int)$val] ?? $val;
                } elseif ($type === 'inlineStr' && isset($c->is->t)) {
                    $val = (string)$c->is->t;
                }

                // Extract column letter from cell reference (e.g., 'A1' -> 0, 'B1' -> 1)
                $ref = (string)$c['r'];
                $colLetters = preg_replace('/[0-9]/', '', $ref);
                $colIndex = $this->columnLetterToIndex($colLetters);

                $row[$colIndex] = trim($val);
            }

            if (!empty($row)) {
                // Fill missing indices with empty strings
                $maxKey = max(array_keys($row));
                $filledRow = [];
                for ($k = 0; $k <= $maxKey; $k++) {
                    $filledRow[$k] = $row[$k] ?? '';
                }
                $rows[] = $filledRow;
            }
        }

        return $rows;
    }

    /**
     * Parse .csv or .txt file into rows.
     */
    public function parseCsv(string $filePath): array
    {
        $rows = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Remove UTF-8 BOM if present
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            while (($data = fgetcsv($handle, 4096, ',')) !== false) {
                // If single column with tabs or semicolons, try fallback
                if (count($data) === 1 && strpos($data[0], "\t") !== false) {
                    $data = explode("\t", $data[0]);
                } elseif (count($data) === 1 && strpos($data[0], ';') !== false) {
                    $data = explode(";", $data[0]);
                }

                $cleaned = array_map('trim', $data);
                // Only add if not entirely empty
                if (count(array_filter($cleaned)) > 0) {
                    $rows[] = $cleaned;
                }
            }
            fclose($handle);
        }

        return $rows;
    }

    /**
     * Process parsed 2D rows into HospitalCharge models.
     */
    public function processRows(array $rows): array
    {
        if (empty($rows)) {
            return [
                'success' => false,
                'message' => 'File contains no rows.',
                'total' => 0,
                'imported' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => ['No rows found in file.'],
            ];
        }

        // 1. Detect Header row
        $headerRowIndex = null;
        $columnMap = [
            'type' => null,
            'category' => null,
            'code' => null,
            'charge' => null,
            'description' => null,
        ];

        for ($i = 0; $i < min(5, count($rows)); $i++) {
            $mapped = $this->identifyHeaders($rows[$i]);
            // If at least category/code and charge are matched
            if ($mapped['charge'] !== null || ($mapped['code'] !== null && $mapped['category'] !== null)) {
                $headerRowIndex = $i;
                $columnMap = $mapped;
                break;
            }
        }

        $dataRows = [];
        if ($headerRowIndex !== null) {
            $dataRows = array_slice($rows, $headerRowIndex + 1);
        } else {
            // Default 4 or 5 columns:
            // 0: Type, 1: Category, 2: Code, 3: Standard Charge, 4: Description
            $columnMap = [
                'type' => 0,
                'category' => 1,
                'code' => 2,
                'charge' => 3,
                'description' => 4,
            ];
            $dataRows = $rows;
        }

        $total = 0;
        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        // Cache existing ChargeTypes & Categories to minimize DB queries
        $chargeTypes = ChargeType::all()->keyBy(function ($item) {
            return strtolower(trim($item->name));
        });

        $chargeCategories = ChargeCategory::all()->keyBy(function ($item) {
            return strtolower(trim($item->charge_category));
        });

        $defaultType = ChargeType::first();
        if (!$defaultType) {
            $defaultType = ChargeType::create(['name' => 'General', 'description' => 'General hospital charges']);
            $chargeTypes->put('general', $defaultType);
        }

        DB::beginTransaction();
        try {
            foreach ($dataRows as $lineIndex => $row) {
                $rowNum = ($headerRowIndex !== null ? $headerRowIndex + 2 : 1) + $lineIndex;

                // Extract fields based on columnMap
                $rawType = $columnMap['type'] !== null && isset($row[$columnMap['type']]) ? trim($row[$columnMap['type']]) : '';
                $rawCategory = $columnMap['category'] !== null && isset($row[$columnMap['category']]) ? trim($row[$columnMap['category']]) : '';
                $rawCode = $columnMap['code'] !== null && isset($row[$columnMap['code']]) ? trim($row[$columnMap['code']]) : '';
                $rawCharge = $columnMap['charge'] !== null && isset($row[$columnMap['charge']]) ? trim($row[$columnMap['charge']]) : '';
                $rawDesc = $columnMap['description'] !== null && isset($row[$columnMap['description']]) ? trim($row[$columnMap['description']]) : '';

                // Skip entirely blank rows
                if (empty($rawType) && empty($rawCategory) && empty($rawCode) && empty($rawCharge)) {
                    continue;
                }

                $total++;

                // Parse Standard Charge amount
                $cleanCharge = preg_replace('/[^0-9.]/', '', $rawCharge);
                $standardCharge = is_numeric($cleanCharge) ? (float)$cleanCharge : 0.00;

                // Fallback for Code & Category
                if (empty($rawCategory) && !empty($rawCode)) {
                    $rawCategory = $rawCode;
                } elseif (empty($rawCode) && !empty($rawCategory)) {
                    $rawCode = $rawCategory;
                }

                if (empty($rawCode) && empty($rawCategory)) {
                    $skipped++;
                    $errors[] = "Row {$rowNum}: Skipped because both Code and Category are empty.";
                    continue;
                }

                // Resolve Charge Type
                $typeObj = null;
                if (!empty($rawType)) {
                    $typeKey = strtolower(trim($rawType));
                    if ($chargeTypes->has($typeKey)) {
                        $typeObj = $chargeTypes->get($typeKey);
                    } else {
                        // Create new ChargeType
                        $typeObj = ChargeType::create([
                            'name' => $rawType,
                            'description' => $rawType . ' charges'
                        ]);
                        $chargeTypes->put($typeKey, $typeObj);
                    }
                } else {
                    $typeObj = $defaultType;
                }

                // Resolve Charge Category
                $catObj = null;
                if (!empty($rawCategory)) {
                    $catKey = strtolower(trim($rawCategory));
                    if ($chargeCategories->has($catKey)) {
                        $catObj = $chargeCategories->get($catKey);
                    } else {
                        // Create new ChargeCategory
                        $catObj = ChargeCategory::create([
                            'charge_category' => $rawCategory,
                            'charge_type'     => $typeObj->id,
                            'description'     => $rawCategory
                        ]);
                        $chargeCategories->put($catKey, $catObj);
                    }
                }

                $chargeTypeId = $typeObj->id;
                $chargeCategoryId = $catObj ? $catObj->id : null;

                // Check for existing HospitalCharge by (code + category) or (code + type)
                $existing = HospitalCharge::where('code', $rawCode)
                    ->where(function ($q) use ($chargeCategoryId, $chargeTypeId) {
                        if ($chargeCategoryId) {
                            $q->where('charge_category', $chargeCategoryId);
                        } else {
                            $q->where('charge_type', $chargeTypeId);
                        }
                    })
                    ->first();

                if (!$existing && !empty($rawCode)) {
                    $existing = HospitalCharge::where('code', $rawCode)->first();
                }

                if ($existing) {
                    $existing->update([
                        'charge_type'     => $chargeTypeId,
                        'charge_category' => $chargeCategoryId ?: $existing->charge_category,
                        'standard_charge' => $standardCharge,
                        'description'     => !empty($rawDesc) ? $rawDesc : $existing->description,
                    ]);
                    $updated++;
                } else {
                    HospitalCharge::create([
                        'charge_type'     => $chargeTypeId,
                        'charge_category' => $chargeCategoryId,
                        'code'            => $rawCode,
                        'standard_charge' => $standardCharge,
                        'description'     => $rawDesc ?: null,
                    ]);
                    $imported++;
                }
            }

            DB::commit();

            return [
                'success' => true,
                'total' => $total,
                'imported' => $imported,
                'updated' => $updated,
                'skipped' => $skipped,
                'errors' => $errors,
                'message' => "Successfully imported {$imported} new charges, updated {$updated} existing charges" . ($skipped > 0 ? " ({$skipped} skipped)" : "") . ".",
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("HospitalChargeImportService Error: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'total' => $total,
                'imported' => $imported,
                'updated' => $updated,
                'skipped' => $skipped,
                'errors' => [$e->getMessage()],
                'message' => "Import failed: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Identify column indices based on header row names.
     */
    protected function identifyHeaders(array $row): array
    {
        $map = [
            'type' => null,
            'category' => null,
            'code' => null,
            'charge' => null,
            'description' => null,
        ];

        foreach ($row as $index => $col) {
            $colName = strtolower(trim((string)$col));
            if (empty($colName)) continue;

            if ($map['type'] === null && preg_match('/^(charge\s*type|type)$/i', $colName)) {
                $map['type'] = $index;
            } elseif ($map['category'] === null && preg_match('/(charge\s*cat|category)/i', $colName)) {
                $map['category'] = $index;
            } elseif ($map['code'] === null && preg_match('/^(code|charge\s*code|name|service\s*name|charge\s*name|test\s*name|particulars?)$/i', $colName)) {
                $map['code'] = $index;
            } elseif ($map['charge'] === null && preg_match('/(standard\s*charge|charge|rate|price|amount|cost|fee|mrp)/i', $colName)) {
                $map['charge'] = $index;
            } elseif ($map['description'] === null && preg_match('/(desc|detail|remark|note)/i', $colName)) {
                $map['description'] = $index;
            }
        }

        return $map;
    }

    /**
     * Convert Excel column letter to 0-based index ('A' -> 0, 'Z' -> 25, 'AA' -> 26).
     */
    protected function columnLetterToIndex(string $letters): int
    {
        $letters = strtoupper($letters);
        $len = strlen($letters);
        $index = 0;
        for ($i = 0; $i < $len; $i++) {
            $index = $index * 26 + (ord($letters[$i]) - ord('A') + 1);
        }
        return $index - 1;
    }

    /**
     * Generate sample CSV template string for hospital charges.
     */
    public function generateSampleCsv(): string
    {
        $output = fopen('php://temp', 'r+');

        // Headers
        fputcsv($output, ['Charge Type', 'Charge Category', 'Code', 'Standard Charge', 'Description']);

        // Sample Rows
        $samples = [
            ['Pathology', 'CBC', 'CBC', '300', 'Complete Blood Count'],
            ['Pathology', 'LFT', 'LFT', '700', 'Liver Function Test'],
            ['Pathology', 'KFT/RFT', 'KFT', '800', 'Kidney Function Test / RFT'],
            ['Pathology', 'CRP', 'CRP', '450', 'C-Reactive Protein'],
            ['Pathology', 'VIRAL MARKER', 'VM', '900', 'Viral Marker Screen'],
            ['Bed / Room Charges', 'ICU Bed', 'ICU Bed', '2500', 'ICU Bed charges per 24 hours'],
            ['Bed / Room Charges', 'General Ward', 'General Ward', '500', 'General Ward per day'],
            ['Bed / Room Charges', 'Private Room (AC)', 'Private AC', '1500', 'Private AC Room per day'],
            ['Doctor / Treatment Charges', 'Consultation', 'OPD Consult', '500', 'Specialist Doctor Consultation'],
            ['Doctor / Treatment Charges', 'Nursing Care', 'Nursing Care', '300', '24 Hr Nursing & Monitoring'],
            ['Doctor / Treatment Charges', 'Emergency Care', 'Emergency STAT', '800', 'Emergency Initial Assessment'],
            ['Tests / Medical Services', 'X-Ray', 'X-Ray Chest', '400', 'Chest X-Ray PA View'],
            ['Tests / Medical Services', 'ECG', 'ECG', '250', '12-Lead Electrocardiogram'],
            ['Tests / Medical Services', 'Nebulization', 'Nebulization', '150', 'Per session nebulization'],
        ];

        foreach ($samples as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }
}

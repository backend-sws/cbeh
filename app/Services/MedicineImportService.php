<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\MedicineBrand;
use App\Models\MedicineCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class MedicineImportService
{
    /**
     * Category alias dictionary for standardizing varied inputs.
     */
    protected array $categoryAliases = [
        // Tablets
        'tab' => 'tablet',
        'tabs' => 'tablet',
        'tablet' => 'tablet',
        'tablets' => 'tablet',
        // Capsules
        'cap' => 'capsule',
        'caps' => 'capsule',
        'capsul' => 'capsule',
        'capsule' => 'capsule',
        'capsules' => 'capsule',
        // Syrups & Suspensions
        'syp' => 'SYRUP',
        'syrup' => 'SYRUP',
        'syrups' => 'SYRUP',
        'susp' => 'SYRUP',
        'suspension' => 'SYRUP',
        'drops' => 'DROP',
        'drop' => 'DROP',
        // Injections
        'inj' => 'INJECTION',
        'injection' => 'INJECTION',
        'injections' => 'INJECTION',
        'amp' => 'INJECTION',
        'ampoule' => 'INJECTION',
        'vial' => 'INJECTION',
        // Ointments & Topicals
        'ointment' => 'OINTMENT',
        'oint' => 'OINTMENT',
        'gel' => 'OINTMENT',
        'cream' => 'OINTMENT',
        // Surgical
        'surjical' => 'SURGICAL',
        'surgical' => 'SURGICAL',
        'surg' => 'SURGICAL',
        // Powder
        'powder' => 'POWDER',
        'poeder' => 'POWDER',
        // Nebulizer
        'neb' => 'NEBULIZER',
        'nebulizer' => 'NEBULIZER',
    ];

    /**
     * Map category to default unit.
     */
    protected array $categoryToUnit = [
        'tablet' => 'tablet',
        'capsule' => 'capsule',
        'syrup' => 'syrup',
        'injection' => 'injection',
        'ointment' => 'ointment',
        'surgical' => 'piece',
        'powder' => 'sachet',
        'drop' => 'drop',
        'nebulizer' => 'respule',
    ];

    /**
     * Import medicines from an uploaded or local file (.xlsx or .csv).
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

        // Determine extension from originalExtension or file path
        $extension = $originalExtension ? strtolower(ltrim($originalExtension, '.')) : strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // If extension is tmp or unknown, auto-detect file content using magic bytes
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
            Log::error("Failed to open zip archive for XLSX: {$filePath}");
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
            // Try locating any worksheet
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
            Log::error("No worksheet found in XLSX: {$filePath}");
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

                $cellRef = (string)$c['r'];
                $colLetter = preg_replace('/[0-9]+/', '', $cellRef);
                $colIndex = $this->columnLetterToIndex($colLetter);
                $row[$colIndex] = trim($val);
            }

            // Fill missing indexes up to max index
            if (!empty($row)) {
                $maxKey = max(array_keys($row));
                for ($k = 0; $k <= $maxKey; $k++) {
                    if (!isset($row[$k])) {
                        $row[$k] = '';
                    }
                }
                ksort($row);
                $rows[] = array_values($row);
            }
        }

        return $rows;
    }

    /**
     * Parse .csv file into rows.
     */
    public function parseCsv(string $filePath): array
    {
        $rows = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 10000, ',')) !== false) {
                // If line was separated by semicolon or tab
                if (count($data) === 1 && (str_contains($data[0], ';') || str_contains($data[0], "\t"))) {
                    $delim = str_contains($data[0], ';') ? ';' : "\t";
                    $data = str_getcsv($data[0], $delim);
                }
                $rows[] = array_map('trim', $data);
            }
            fclose($handle);
        }
        return $rows;
    }

    /**
     * Convert Excel column letters (A, B, AA...) to 0-based column index.
     */
    protected function columnLetterToIndex(string $letter): int
    {
        $letter = strtoupper($letter);
        $len = strlen($letter);
        $index = 0;
        for ($i = 0; $i < $len; $i++) {
            $index = $index * 26 + (ord($letter[$i]) - 64);
        }
        return $index - 1;
    }

    /**
     * Check if the first row represents a header row.
     */
    protected function isHeaderRow(array $firstRow): bool
    {
        $headerKeywords = [
            'company', 'brand', 'mfg', 'manufacturer',
            'medicine', 'item', 'drug', 'name', 'product',
            'category', 'type', 'sysrup', 'syrup', 'tablet',
            'strip', 'strip size', 'stripsize', 'pack', 'pack size', 'size', 'qty', 'quantity'
        ];

        $matched = 0;
        foreach ($firstRow as $cell) {
            $clean = strtolower(trim((string)$cell));
            foreach ($headerKeywords as $kw) {
                if ($clean === $kw || str_contains($clean, $kw)) {
                    $matched++;
                    break;
                }
            }
        }

        // If at least 2 cells match common header keywords, it's a header row
        return $matched >= 2;
    }

    /**
     * Process parsed rows and import into database.
     */
    protected function processRows(array $rows): array
    {
        $importedCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $errors = [];

        // Check if row 0 is header
        $startIndex = 0;
        if (isset($rows[0]) && $this->isHeaderRow($rows[0])) {
            $startIndex = 1;
        }

        // Cache existing brands and categories to avoid redundant DB queries
        $brandsCache = [];
        foreach (MedicineBrand::all() as $b) {
            $brandsCache[strtolower(trim($b->brand))] = $b->id;
        }

        $categoriesCache = [];
        foreach (MedicineCategory::all() as $c) {
            $categoriesCache[strtolower(trim($c->medicine_category))] = $c->id;
        }

        DB::beginTransaction();
        try {
            for ($i = $startIndex; $i < count($rows); $i++) {
                $row = $rows[$i];

                // Ensure at least 4 items in row
                $companyRaw  = trim((string)($row[0] ?? ''));
                $medicineRaw = trim((string)($row[1] ?? ''));
                $categoryRaw = trim((string)($row[2] ?? ''));
                $stripRaw    = trim((string)($row[3] ?? ''));

                // Skip completely empty rows
                if ($companyRaw === '' && $medicineRaw === '' && $categoryRaw === '') {
                    $skippedCount++;
                    continue;
                }

                // If medicine name is empty, record error and continue
                if ($medicineRaw === '') {
                    $errors[] = "Row " . ($i + 1) . ": Skipped because medicine name is empty.";
                    $skippedCount++;
                    continue;
                }

                // 1. Resolve Company / Brand
                $companyName = $companyRaw !== '' ? $companyRaw : 'GENERAL';
                $brandKey = strtolower($companyName);
                if (isset($brandsCache[$brandKey])) {
                    $brandId = $brandsCache[$brandKey];
                } else {
                    $brand = MedicineBrand::create([
                        'brand' => $companyName,
                    ]);
                    $brandId = $brand->id;
                    $brandsCache[$brandKey] = $brandId;
                }

                // 2. Resolve Category & Unit
                $categoryName = $categoryRaw !== '' ? $categoryRaw : 'TABLET';
                $normalizedCatName = $this->normalizeCategory($categoryName);
                $catKey = strtolower($normalizedCatName);

                if (isset($categoriesCache[$catKey])) {
                    $categoryId = $categoriesCache[$catKey];
                } else {
                    $newCat = MedicineCategory::create([
                        'medicine_category' => $normalizedCatName,
                        'status' => 1,
                    ]);
                    $categoryId = $newCat->id;
                    $categoriesCache[$catKey] = $categoryId;
                }

                // Determine unit
                $unit = $this->determineUnit($normalizedCatName, $medicineRaw);

                // 3. Resolve Strip Size (Pieces per strip/pack)
                $cleanStripNum = preg_replace('/[^0-9]/', '', $stripRaw);
                $stripQuantity = !empty($cleanStripNum) ? max(1, (int)$cleanStripNum) : 1;

                // 4. Extract Salt Composition if enclosed in brackets: e.g. "VERTIN 16MG [BILASTIN 16]"
                $saltComposition = null;
                if (preg_match('/\[([^\]]+)\]?/', $medicineRaw, $saltMatches)) {
                    $saltComposition = trim($saltMatches[1]);
                }

                // 5. Look up existing medicine by name (case-insensitive)
                $existing = Medicine::whereRaw('LOWER(TRIM(medicine)) = ?', [strtolower($medicineRaw)])->first();

                if ($existing) {
                    // Update existing medicine details
                    $existing->brand = $brandId;
                    $existing->category = $categoryId;
                    $existing->strip_quantity = $stripQuantity;
                    if (!empty($unit)) {
                        $existing->unit = $unit;
                    }
                    if (!empty($saltComposition) && empty($existing->salt_composition)) {
                        $existing->salt_composition = $saltComposition;
                    }
                    // Re-calculate total pieces based on available strips
                    $availableStrips = (int)($existing->available_quantity ?? 0);
                    $existing->total_quantity = $availableStrips * $stripQuantity;
                    $existing->save();

                    $updatedCount++;
                } else {
                    // Create new medicine
                    Medicine::create([
                        'medicine' => $medicineRaw,
                        'brand' => $brandId,
                        'category' => $categoryId,
                        'unit' => $unit,
                        'salt_composition' => $saltComposition,
                        'buying_price' => 0.00,
                        'selling_price' => 0.00,
                        'quantity' => 0,
                        'available_quantity' => 0,
                        'total_quantity' => 0,
                        'strip_quantity' => $stripQuantity,
                    ]);

                    $importedCount++;
                }
            }

            DB::commit();

            return [
                'success' => true,
                'message' => "Successfully processed: {$importedCount} created, {$updatedCount} updated" . ($skippedCount > 0 ? ", {$skippedCount} skipped" : "") . ".",
                'total' => count($rows) - $startIndex,
                'imported' => $importedCount,
                'updated' => $updatedCount,
                'skipped' => $skippedCount,
                'errors' => $errors,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Medicine Bulk Import Error: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => "Import failed: " . $e->getMessage(),
                'total' => count($rows) - $startIndex,
                'imported' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => [$e->getMessage()],
            ];
        }
    }

    /**
     * Normalize category name using aliases or predefined formats.
     */
    protected function normalizeCategory(string $rawCategory): string
    {
        $clean = strtolower(trim($rawCategory));

        // Check alias mapping
        if (isset($this->categoryAliases[$clean])) {
            return $this->categoryAliases[$clean];
        }

        // Check if substring matches known keywords
        foreach ($this->categoryAliases as $keyword => $canonical) {
            if (str_contains($clean, $keyword)) {
                return $canonical;
            }
        }

        // Return title case if unknown
        return ucfirst(trim($rawCategory));
    }

    /**
     * Determine unit string for the medicine.
     */
    protected function determineUnit(string $category, string $medicineName): string
    {
        $catLower = strtolower($category);
        if (isset($this->categoryToUnit[$catLower])) {
            return $this->categoryToUnit[$catLower];
        }

        $medLower = strtolower($medicineName);
        if (str_contains($medLower, 'tab')) return 'tablet';
        if (str_contains($medLower, 'cap')) return 'capsule';
        if (str_contains($medLower, 'syp') || str_contains($medLower, 'syrup')) return 'syrup';
        if (str_contains($medLower, 'inj')) return 'injection';
        if (str_contains($medLower, 'gel') || str_contains($medLower, 'cream') || str_contains($medLower, 'ointment')) return 'ointment';
        if (str_contains($medLower, 'drop')) return 'drop';

        return 'tablet';
    }

    /**
     * Generate sample CSV template content.
     */
    public function generateSampleCsv(): string
    {
        $output = fopen('php://temp', 'r+');
        fputcsv($output, ['Company', 'Medicine Name', 'Category', 'Strip Size']);
        fputcsv($output, ['ABBO', 'VERTIN 16MG [BILASTIN 16]', 'TABLET', '10']);
        fputcsv($output, ['ABBO', 'DUPHALACK', 'SYRUP', '1']);
        fputcsv($output, ['ALKEM', 'AZITRAL 500', 'TABLET', '5']);
        fputcsv($output, ['SUN PHARMA', 'LEVEPIL [LEVETIRACETAM 100MG]', 'INJECTION', '5']);
        fputcsv($output, ['ARISTO', 'MEGAHEAL JEL', 'OINTMENT', '1']);
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        return $csv;
    }
}

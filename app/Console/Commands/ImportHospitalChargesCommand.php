<?php

namespace App\Console\Commands;

use App\Services\HospitalChargeImportService;
use Illuminate\Console\Command;

class ImportHospitalChargesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hospital-charge:import {file? : Optional path to Excel (.xlsx) or CSV file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import hospital charges in bulk from an Excel (.xlsx) or CSV file';

    /**
     * Execute the console command.
     */
    public function handle(HospitalChargeImportService $service): int
    {
        $fileArg = $this->argument('file');

        if ($fileArg) {
            $filePath = file_exists($fileArg) ? $fileArg : base_path($fileArg);
        } else {
            // Check default files in root
            $possibleFiles = ['charges.xlsx', 'hospital_charges.xlsx', 'charges.csv', 'hospital_charges.csv', 'TALHA.xlsx'];
            $filePath = null;
            foreach ($possibleFiles as $file) {
                if (file_exists(base_path($file))) {
                    $filePath = base_path($file);
                    break;
                }
            }
        }

        if (!$filePath || !file_exists($filePath)) {
            $this->error("File not found! Please specify a valid file path or place charges.xlsx in the project root.");
            return Command::FAILURE;
        }

        $this->info("Starting hospital charges import from: {$filePath}");

        $result = $service->import($filePath);

        if (!$result['success']) {
            $this->error("Import Failed: " . $result['message']);
            if (!empty($result['errors'])) {
                foreach ($result['errors'] as $err) {
                    $this->warn("- " . $err);
                }
            }
            return Command::FAILURE;
        }

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Rows Processed', $result['total']],
                ['New Charges Created', $result['imported']],
                ['Existing Charges Updated', $result['updated']],
                ['Skipped Rows', $result['skipped']],
            ]
        );

        if (!empty($result['errors'])) {
            $this->warn("\nWarnings / Errors:");
            foreach ($result['errors'] as $err) {
                $this->line("- " . $err);
            }
        }

        $this->info("\n" . $result['message']);
        return Command::SUCCESS;
    }
}

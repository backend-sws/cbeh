<?php

namespace App\Console\Commands;

use App\Services\MedicineImportService;
use Illuminate\Console\Command;

class ImportMedicinesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medicine:import {file? : Optional path to Excel (.xlsx) or CSV file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import medicines in bulk from an Excel (.xlsx) or CSV file';

    /**
     * Execute the console command.
     */
    public function handle(MedicineImportService $service): int
    {
        $fileArg = $this->argument('file');

        if ($fileArg) {
            $filePath = file_exists($fileArg) ? $fileArg : base_path($fileArg);
        } else {
            // Check default file in root
            $possibleFiles = ['TALHA.xlsx', 'talha.xlsx', 'tahlha.xlsx', 'medicines.xlsx', 'medicines.csv'];
            $filePath = null;
            foreach ($possibleFiles as $file) {
                if (file_exists(base_path($file))) {
                    $filePath = base_path($file);
                    break;
                }
            }
        }

        if (!$filePath || !file_exists($filePath)) {
            $this->error("File not found! Please specify a valid file path or ensure TALHA.xlsx is in the project root.");
            return Command::FAILURE;
        }

        $this->info("Starting medicine import from: {$filePath}");

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
                ['New Medicines Created', $result['imported']],
                ['Existing Medicines Updated', $result['updated']],
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

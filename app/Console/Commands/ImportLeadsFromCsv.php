<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;

class ImportLeadsFromCsv extends Command
{
    protected $signature = 'leads:import 
                            {file : Path to the CSV file}
                            {--d|debug : Just parse and show the data without importing}
                            {--s|skip-header : Skip the first row (header)}
                            {--delimiter=, : CSV delimiter character}';
    
    protected $description = 'Import leads from a CSV file';

    public function handle()
    {
        $filePath = $this->argument('file');
        $debug = $this->option('debug');
        $skipHeader = $this->option('skip-header');
        $delimiter = $this->option('delimiter');
        
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return Command::FAILURE;
        }
        
        $this->info("Processing file: {$filePath}");
        $this->info("Using delimiter: " . ($delimiter === "\t" ? "TAB" : $delimiter));
        
        if ($debug) {
            $this->info("DEBUG MODE: Will not import any data");
        }
        
        // Open and read file
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->error("Could not open file");
            return Command::FAILURE;
        }
        
        // Read header row if needed
        if ($skipHeader) {
            $header = fgetcsv($handle, 0, $delimiter);
            $this->info("Skipped header row: " . implode(', ', $header));
        }
        
        $rowNumber = $skipHeader ? 1 : 0;
        $successCount = 0;
        $errorCount = 0;
        
        // Begin transaction if not in debug mode
        if (!$debug) {
            DB::beginTransaction();
        }
        
        try {
            while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
                $rowNumber++;
                
                // Skip empty rows
                if (empty(array_filter($data))) {
                    $this->comment("Row {$rowNumber}: Empty row, skipping");
                    continue;
                }
                
                // Show the data
                $this->line("\nRow {$rowNumber}:");
                $this->table(
                    ['Column', 'Value'], 
                    array_map(function($index, $value) {
                        return ["Column {$index}", $value];
                    }, array_keys($data), $data)
                );
                
                // Process row
                try {
                    // Get first and last name
                    $fullName = $data[0] ?? '';
                    if (empty($fullName)) {
                        $fullName = $data[1] ?? '';
                    }
                    
                    // Parse name
                    $firstName = 'Imported';
                    $lastName = 'Lead';
                    
                    if (!empty($fullName)) {
                        $nameParts = explode(' ', $fullName, 2);
                        $firstName = $nameParts[0];
                        $lastName = $nameParts[1] ?? '';
                    }
                    
                    // Get other data (adapt column indexes as needed)
                    $email = $data[2] ?? null;
                    $phone = $data[3] ?? null;
                    $mobile = $data[4] ?? null;
                    $notes = $data[7] ?? null;
                    
                    $leadData = [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $email,
                        'phone' => $phone,
                        'mobile' => $mobile,
                        'status' => 'new',
                        'notes' => $notes,
                    ];
                    
                    $this->info("Processed data: " . json_encode($leadData));
                    
                    if (!$debug) {
                        Lead::create($leadData);
                        $successCount++;
                        $this->info("Row {$rowNumber}: Imported successfully");
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    $this->error("Row {$rowNumber}: Error - " . $e->getMessage());
                }
            }
            
            if (!$debug && $successCount > 0) {
                DB::commit();
                $this->info("\nImport completed: {$successCount} leads imported, {$errorCount} failed");
            } else if (!$debug) {
                DB::rollBack();
                $this->info("\nNo leads imported. Transaction rolled back.");
            } else {
                $this->info("\nDebug completed. Processed {$rowNumber} rows");
            }
            
            fclose($handle);
            return Command::SUCCESS;
        } catch (\Exception $e) {
            if (!$debug) {
                DB::rollBack();
            }
            fclose($handle);
            $this->error("Error processing file: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

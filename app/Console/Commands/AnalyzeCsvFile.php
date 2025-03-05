<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;

class AnalyzeCsvFile extends Command
{
    protected $signature = 'csv:analyze {file : Path to the CSV file}';
    protected $description = 'Analyze a CSV file and provide recommendations for import';
    
    public function handle()
    {
        $filePath = $this->argument('file');
        
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return Command::FAILURE;
        }
        
        $this->info("Analyzing file: {$filePath}");
        
        try {
            // Try to detect delimiter
            $content = file_get_contents($filePath, false, null, 0, 10240); // Read first 10KB
            $delimiters = [',', ';', "\t", '|'];
            $delimiterCounts = [];
            
            foreach ($delimiters as $delimiter) {
                $delimiterCounts[$delimiter] = substr_count($content, $delimiter);
            }
            
            $detectedDelimiter = array_keys($delimiterCounts, max($delimiterCounts))[0];
            
            // Read the CSV
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setDelimiter($detectedDelimiter);
            
            // Try to detect if there's a header row
            $hasHeader = true;
            $firstRow = $csv->fetchOne();
            
            if (empty($firstRow)) {
                $this->error('The file appears to be empty.');
                return Command::FAILURE;
            }
            
            $this->info("Detected delimiter: " . ($detectedDelimiter === "\t" ? 'TAB' : $detectedDelimiter));
            $this->info("First row has " . count($firstRow) . " columns");
            $this->table(['Column Index', 'Column Name'], array_map(function($index, $value) {
                return [$index, $value];
            }, array_keys($firstRow), $firstRow));
            
            // Analyze if it's likely a header row
            $numericCount = 0;
            foreach ($firstRow as $value) {
                if (is_numeric($value)) {
                    $numericCount++;
                }
            }
            
            $headerProbability = ($numericCount / count($firstRow) < 0.5) ? 'High' : 'Low';
            $this->info("Probability that the first row is a header: {$headerProbability}");
            
            // Show a few sample rows
            $this->info("\nSample data (first 3 rows):");
            $records = $csv->setHeaderOffset(0)->fetchAll();
            $sampleData = array_slice(iterator_to_array($records), 0, 3);
            
            if (!empty($sampleData)) {
                // Get all keys from all rows (some rows might have different keys)
                $headers = [];
                foreach ($sampleData as $row) {
                    $headers = array_merge($headers, array_keys($row));
                }
                $headers = array_unique($headers);
                
                // Format the data for display
                $tableData = [];
                foreach ($sampleData as $index => $row) {
                    $rowData = [];
                    foreach ($headers as $header) {
                        $rowData[] = $row[$header] ?? '';
                    }
                    $tableData[] = $rowData;
                }
                
                $this->table($headers, $tableData);
            }
            
            // Analyze column mappings
            $this->info("\nSuggested field mappings:");
            
            $knownFields = [
                'first_name' => ['first', 'first name', 'firstname', 'fname', 'forename', 'first_name'],
                'last_name' => ['last', 'last name', 'lastname', 'lname', 'surname', 'last_name'],
                'email' => ['email', 'e-mail', 'email address', 'mail'],
                'phone' => ['phone', 'telephone', 'mobile', 'cell', 'contact', 'phone number'],
                'status' => ['status', 'lead status', 'lead_status', 'state'],
                'source' => ['source', 'lead source', 'lead_source', 'channel'],
                'budget' => ['budget', 'price', 'amount', 'value'],
                'notes' => ['notes', 'comments', 'description', 'additional info']
            ];
            
            $columnMappings = [];
            foreach ($firstRow as $index => $columnName) {
                $columnName = strtolower(trim($columnName));
                $mappedField = null;
                
                foreach ($knownFields as $field => $patterns) {
                    if (in_array($columnName, $patterns) || preg_match('/\b(' . implode('|', $patterns) . ')\b/i', $columnName)) {
                        $mappedField = $field;
                        break;
                    }
                }
                
                $columnMappings[] = [$index, $columnName, $mappedField ?? 'unknown'];
            }
            
            $this->table(['Column Index', 'CSV Column', 'Mapped To'], $columnMappings);
            
            // Give recommendations
            $this->info("\nRecommendations:");
            $recommendations = [];
            
            // Check if essential fields are mapped
            $mapped = array_column($columnMappings, 2);
            if (!in_array('first_name', $mapped) && !in_array('last_name', $mapped)) {
                $recommendations[] = "⚠️ No name fields detected. Make sure there's a column for first name or last name.";
            }
            
            if (!in_array('email', $mapped) && !in_array('phone', $mapped)) {
                $recommendations[] = "⚠️ No contact fields detected. Include an email or phone column for contact info.";
            }
            
            if (in_array('unknown', $mapped)) {
                $recommendations[] = "⚠️ Some columns couldn't be automatically mapped. Consider renaming them to match standard field names.";
            }
            
            if (empty($recommendations)) {
                $recommendations[] = "✅ All essential fields seem to be properly mapped!";
            }
            
            foreach ($recommendations as $recommendation) {
                $this->line($recommendation);
            }
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Error analyzing file: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

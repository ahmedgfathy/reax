<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PropertiesTableSeeder extends Seeder
{
    public function run()
    {
        // Check if properties already exist
        if (DB::table('properties')->count() > 0) {
            $this->command->info('Properties table already has records. Skipping seeding.');
            return;
        }

        try {
            // Get structure of properties table
            $tableColumns = $this->getTableStructure('properties');
            
            // Debug info
            $this->command->info('Detected property table columns: ' . implode(', ', array_keys($tableColumns)));
            
            // Default values for each property type
            $properties = [
                // Modern Apartment
                [
                    'title' => 'Modern Apartment in New Cairo',
                    'name' => 'Modern Apartment',
                    'description' => 'Beautiful modern apartment with high-end finishes',
                    'location' => 'New Cairo',
                    'price' => 1500000,
                    'currency' => 'EGP',
                    'unit_for' => 'sale',
                    'rooms' => 3,
                    'bedrooms' => 2,
                    'bathrooms' => 2,
                    'area' => 120,
                    'unit_area' => 120,
                    'type' => 'apartment',
                    'status' => 'available',
                    'is_featured' => true
                ],
                
                // Luxury Villa
                [
                    'title' => 'Luxury Villa in Giza',
                    'name' => 'Luxury Villa',
                    'description' => 'Spacious luxury villa with private garden and pool',
                    'location' => 'Giza',
                    'price' => 5000000,
                    'currency' => 'EGP',
                    'unit_for' => 'sale',
                    'rooms' => 5,
                    'bedrooms' => 4,
                    'bathrooms' => 4,
                    'area' => 300,
                    'unit_area' => 300,
                    'land_area' => 500,
                    'type' => 'villa',
                    'status' => 'available',
                    'is_featured' => true
                ],
                
                // Office Space
                [
                    'title' => 'Office Space in Downtown',
                    'name' => 'Downtown Office',
                    'description' => 'Prime location office space for rent',
                    'location' => 'Downtown',
                    'price' => 20000,
                    'currency' => 'EGP',
                    'unit_for' => 'rent',
                    'rooms' => 2,
                    'bedrooms' => 0,
                    'bathrooms' => 1,
                    'area' => 80,
                    'unit_area' => 80,
                    'type' => 'office',
                    'status' => 'available',
                    'is_featured' => false
                ]
            ];
            
            // Timestamps for all records
            $now = now();
            
            // Process each property
            foreach ($properties as $index => $property) {
                $insertData = []; 
                
                // Check which properties actually exist in the table
                // and ensure all required fields have values
                foreach ($tableColumns as $column => $info) {
                    if (isset($property[$column])) {
                        // Use the provided value
                        $insertData[$column] = $property[$column];
                    } else if ($info['nullable'] === 'NO' && $info['default'] === null) {
                        // Required field not provided - use sensible defaults
                        switch ($column) {
                            case 'rooms':
                            case 'bedrooms':
                            case 'bathrooms':
                                $insertData[$column] = 0;
                                break;
                            case 'price':
                            case 'area':
                            case 'unit_area':
                                $insertData[$column] = 0;
                                break;
                            case 'currency':
                                $insertData[$column] = 'EGP';
                                break;
                            case 'status':
                                $insertData[$column] = 'available';
                                break;
                            case 'unit_for':
                                $insertData[$column] = 'sale';
                                break;
                            case 'type':
                                $insertData[$column] = 'other';
                                break;
                            default:
                                // For other required string fields
                                if (strpos($info['type'], 'varchar') !== false || 
                                    strpos($info['type'], 'char') !== false || 
                                    strpos($info['type'], 'text') !== false) {
                                    $insertData[$column] = 'Default ' . ucfirst($column);
                                } else {
                                    $insertData[$column] = 0; // Default for numeric fields
                                }
                        }
                    }
                }
                
                // Always add timestamps
                $insertData['created_at'] = $now;
                $insertData['updated_at'] = $now;
                
                // Insert the property
                $this->command->info("Inserting property: {$property['name']}");
                DB::table('properties')->insert($insertData);
                
                // Output which fields were used
                $this->command->info("Fields used: " . implode(', ', array_keys($insertData)));
            }
            
            $this->command->info('Properties table seeded with 3 records!');
            
        } catch (\Exception $e) {
            $this->command->error('Error seeding properties: ' . $e->getMessage());
            $this->command->error('Line: ' . $e->getLine());
            throw $e;
        }
    }
    
    /**
     * Get table structure with column details
     */
    private function getTableStructure($tableName)
    {
        $columns = [];
        
        // Fetch column information
        $columnInfo = DB::select("SHOW COLUMNS FROM {$tableName}");
        
        foreach ($columnInfo as $column) {
            $columns[$column->Field] = [
                'type' => $column->Type,
                'nullable' => $column->Null,
                'default' => $column->Default
            ];
        }
        
        return $columns;
    }
}

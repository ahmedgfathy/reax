<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PropertiesTableSeeder extends Seeder
{
    public function run()
    {
        // Check if properties exist but fewer than desired minimum (50)
        $propertyCount = DB::table('properties')->count();
        
        if ($propertyCount >= 50) {
            $this->command->info("Properties table already has {$propertyCount} records. Skipping seeding.");
            return;
        } elseif ($propertyCount > 0) {
            $this->command->info("Properties table has only {$propertyCount} records. Adding more to reach 50...");
            // We'll continue execution to add more properties
        }

        try {
            // Get structure of properties table
            $tableColumns = $this->getTableStructure('properties');
            
            // Check if area is numeric (decimal/float/integer)
            $isAreaNumeric = false;
            if (isset($tableColumns['area'])) {
                $areaType = strtolower($tableColumns['area']['type']);
                $isAreaNumeric = strpos($areaType, 'int') !== false || 
                                strpos($areaType, 'decimal') !== false || 
                                strpos($areaType, 'float') !== false || 
                                strpos($areaType, 'double') !== false;
                
                $this->command->info("Area column type: {$areaType}, isNumeric: " . ($isAreaNumeric ? 'true' : 'false'));
            }
            
            // Debug info
            $this->command->info('Detected property table columns: ' . implode(', ', array_keys($tableColumns)));
            
            // Property types
            $propertyTypes = ['apartment', 'villa', 'duplex', 'penthouse', 'studio', 'office', 'shop', 'land'];
            
            // Locations
            $locations = [
                'New Cairo', 'Sheikh Zayed', 'El Gouna', '6th of October', 
                'Maadi', 'Heliopolis', 'Nasr City', 'Downtown', 'Zamalek', 
                'Garden City', 'Alexandria', 'North Coast', 'El Alamein', 'Hurghada'
            ];
            
            // Status options
            $statuses = ['available', 'sold', 'pending', 'reserved'];
            
            // Specific areas within locations (renamed to avoid confusion)
            $locationAreas = [
                'New Cairo' => ['Fifth Settlement', 'First Settlement', 'Katameya Heights', 'Mountain View'],
                'Sheikh Zayed' => ['Beverly Hills', 'Zayed Dunes', 'Zayed Regency'],
                'Maadi' => ['Degla', 'Sarayat', 'New Maadi'],
                '6th of October' => ['October Plaza', 'Central Park', 'Dreamland'],
                'Heliopolis' => ['Korba', 'Triumph', 'Merghany'],
                'Zamalek' => ['Gezira', 'Aisha Fahmy'],
                'Alexandria' => ['San Stefano', 'Montaza', 'Gleem'],
                'North Coast' => ['Marina', 'Marassi', 'Hacienda Bay'],
            ];
            
            // Currency options with EGP as default
            $currencies = ['EGP', 'USD', 'EUR'];
            $currencyWeights = [85, 10, 5]; // 85% chance of EGP
            
            // Prepare property data
            $properties = [];
            $now = now();
            
            // Calculate how many more properties we need
            $additionalPropertiesNeeded = 50 - $propertyCount;
            
            // Inside the run() method, this seeder creates the needed number of property units:
            for ($i = 0; $i < $additionalPropertiesNeeded; $i++) {
                // Select random property type
                $type = $propertyTypes[array_rand($propertyTypes)];
                
                // Select random location
                $location = $locations[array_rand($locations)];
                
                // Select random area within location if available (as string for 'area_name')
                $areaName = isset($locationAreas[$location]) ? $locationAreas[$location][array_rand($locationAreas[$location])] : $location;
                
                // Generate numeric area value for the actual area column (if it's numeric)
                $numericAreaValue = $this->generateAreaSize($type);
                
                // Determine if for sale or rent (70% sale, 30% rent)
                $unitFor = (rand(1, 100) <= 70) ? 'sale' : 'rent';
                
                // Set realistic price ranges based on type and whether for sale or rent
                $price = $this->generateRealisticPrice($type, $unitFor);
                
                // Select currency (weighted random)
                $currency = $this->getRandomWeighted($currencies, $currencyWeights);
                
                // Generate realistic room counts based on type
                $rooms = $this->generateRooms($type);
                $bedrooms = ($type == 'office' || $type == 'shop' || $type == 'land') ? 0 : ($rooms - rand(1, 2));
                $bedrooms = max(1, $bedrooms); // Ensure minimum 1 bedroom for residential
                $bathrooms = max(1, min($bedrooms, rand(1, $bedrooms + 1)));
                
                // Generate realistic area size based on type
                $unitArea = $this->generateAreaSize($type);
                
                // Set featured flag (20% chance to be featured)
                $isFeatured = (rand(1, 100) <= 20);
                
                // Set status with 70% available
                $status = (rand(1, 100) <= 70) ? 'available' : $statuses[array_rand(array_slice($statuses, 1, 3))];
                
                // Set title variations for more realistic names
                $title = $this->generatePropertyTitle($type, $bedrooms, $areaName, $location);
                
                // Generate a property with all fields that exist in the table
                $property = [
                    'title' => $title,
                    'name' => $title,
                    'description' => $this->generateDescription($type, $bedrooms, $unitArea, $areaName, $location),
                    'location' => $location,
                    'address' => "Address in $areaName, $location",
                    'price' => $price,
                    'currency' => $currency,
                    'unit_for' => $unitFor,
                    'status' => $status,
                    'type' => $type,
                    'rooms' => $rooms,
                    'bedrooms' => $bedrooms,
                    'bathrooms' => $bathrooms,
                    'unit_area' => $unitArea,
                    'land_area' => ($type == 'land' || $type == 'villa') ? $unitArea * rand(1, 3) : $unitArea,
                    'year_built' => rand(2000, 2023),
                    'floor' => $type == 'apartment' || $type == 'penthouse' || $type == 'studio' ? rand(1, 20) : 0,
                    'is_featured' => $isFeatured,
                    'reference_number' => 'REF-' . strtoupper(Str::random(6)),
                    'created_at' => $now->copy()->subDays(rand(1, 365)),
                    'updated_at' => $now,
                ];
                
                // Handle area field correctly based on its type
                if (isset($tableColumns['area'])) {
                    if ($isAreaNumeric) {
                        // If area is numeric, use the numeric area value
                        $property['area'] = $numericAreaValue;
                    } else {
                        // If area is not numeric, use area name as a string
                        $property['area'] = $areaName;
                    }
                }
                
                // Special handling for compound_name if it exists
                if (isset($tableColumns['compound_name'])) {
                    $property['compound_name'] = $areaName;
                }
                
                // Filter the property data to match existing columns
                $insertData = [];
                foreach ($property as $key => $value) {
                    if (isset($tableColumns[$key])) {
                        $insertData[$key] = $value;
                    }
                }
                
                // Add to properties array
                $properties[] = $insertData;
            }
            
            // Insert all properties
            $this->command->info('Inserting ' . count($properties) . ' additional property records...');
            
            // Insert in chunks to avoid memory issues
            foreach (array_chunk($properties, 10) as $chunk) {
                DB::table('properties')->insert($chunk);
            }
            
            $finalCount = DB::table('properties')->count();
            $this->command->info("Successfully added properties. Total count is now {$finalCount}!");
            
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
    
    /**
     * Generate realistic price based on property type and sale/rent status
     */
    private function generateRealisticPrice($type, $unitFor)
    {
        if ($unitFor == 'sale') {
            // Sale prices
            switch ($type) {
                case 'apartment':
                    return rand(70, 250) * 10000; // 700K to 2.5M
                case 'villa':
                    return rand(150, 800) * 10000; // 1.5M to 8M
                case 'duplex':
                    return rand(120, 400) * 10000; // 1.2M to 4M
                case 'penthouse':
                    return rand(200, 900) * 10000; // 2M to 9M
                case 'studio':
                    return rand(50, 150) * 10000; // 500K to 1.5M
                case 'office':
                    return rand(100, 500) * 10000; // 1M to 5M
                case 'shop':
                    return rand(80, 300) * 10000; // 800K to 3M
                case 'land':
                    return rand(300, 2000) * 10000; // 3M to 20M
                default:
                    return rand(100, 300) * 10000;
            }
        } else {
            // Rent prices (monthly)
            switch ($type) {
                case 'apartment':
                    return rand(50, 150) * 100; // 5K to 15K
                case 'villa':
                    return rand(100, 300) * 100; // 10K to 30K
                case 'duplex':
                    return rand(80, 200) * 100; // 8K to 20K
                case 'penthouse':
                    return rand(120, 350) * 100; // 12K to 35K
                case 'studio':
                    return rand(30, 80) * 100; // 3K to 8K
                case 'office':
                    return rand(70, 250) * 100; // 7K to 25K
                case 'shop':
                    return rand(50, 200) * 100; // 5K to 20K
                default:
                    return rand(60, 150) * 100;
            }
        }
    }
    
    /**
     * Generate room count based on property type
     */
    private function generateRooms($type)
    {
        switch ($type) {
            case 'studio':
                return 1;
            case 'apartment':
                return rand(2, 5);
            case 'duplex':
                return rand(4, 7);
            case 'villa':
                return rand(5, 10);
            case 'penthouse':
                return rand(3, 6);
            case 'office':
                return rand(2, 8);
            case 'shop':
                return rand(1, 3);
            case 'land':
                return 0;
            default:
                return rand(2, 5);
        }
    }
    
    /**
     * Generate realistic area size based on property type
     */
    private function generateAreaSize($type)
    {
        switch ($type) {
            case 'studio':
                return rand(30, 60);
            case 'apartment':
                return rand(80, 200);
            case 'duplex':
                return rand(150, 300);
            case 'villa':
                return rand(200, 600);
            case 'penthouse':
                return rand(150, 400);
            case 'office':
                return rand(80, 500);
            case 'shop':
                return rand(40, 150);
            case 'land':
                return rand(300, 5000);
            default:
                return rand(80, 200);
        }
    }
    
    /**
     * Generate property title with variations
     */
    private function generatePropertyTitle($type, $bedrooms, $area, $location)
    {
        $prefix = [
            'Luxurious', 'Modern', 'Spacious', 'Elegant', 'Cozy', 'Beautiful',
            'Stunning', 'Premium', 'Exclusive', 'High-End', 'Unique', 'Amazing'
        ];
        
        $locationPrefix = rand(0, 1) ? "in $area" : "in $location";
        
        switch ($type) {
            case 'studio':
                return $prefix[array_rand($prefix)] . " Studio $locationPrefix";
            case 'apartment':
                return $prefix[array_rand($prefix)] . " $bedrooms Bedroom Apartment $locationPrefix";
            case 'duplex':
                return $prefix[array_rand($prefix)] . " $bedrooms Bedroom Duplex $locationPrefix";
            case 'villa':
                return $prefix[array_rand($prefix)] . " $bedrooms Bedroom Villa $locationPrefix";
            case 'penthouse':
                return $prefix[array_rand($prefix)] . " $bedrooms Bedroom Penthouse $locationPrefix";
            case 'office':
                return $prefix[array_rand($prefix)] . " Office Space $locationPrefix";
            case 'shop':
                return $prefix[array_rand($prefix)] . " Retail Shop $locationPrefix";
            case 'land':
                return $prefix[array_rand($prefix)] . " Land Plot $locationPrefix";
            default:
                return $prefix[array_rand($prefix)] . " Property $locationPrefix";
        }
    }
    
    /**
     * Generate detailed description based on property attributes
     */
    private function generateDescription($type, $bedrooms, $area, $areaName, $location)
    {
        $sentences = [
            "This $type offers a wonderful opportunity in the heart of $areaName, $location.",
            "Located in a prime area of $areaName, this property boasts $bedrooms bedrooms and approximately $area square meters of space.",
            "Perfect for those seeking comfort and convenience in $location.",
            "The property features modern finishes and is well-maintained throughout.",
            "Close to all amenities including schools, shopping centers, and public transportation.",
            "Enjoy the benefits of living in one of the most sought-after neighborhoods in $location.",
            "This property won't stay on the market long!",
            "Call today to schedule a viewing of this exceptional opportunity.",
            "Ideal for both investors and end-users alike."
        ];
        
        // Add type-specific sentences
        switch ($type) {
            case 'villa':
                $sentences[] = "This villa features a beautiful garden and ample outdoor space for entertainment.";
                $sentences[] = "Private parking and security make this an ideal family home.";
                break;
            case 'apartment':
                $sentences[] = "This apartment offers panoramic views and modern building amenities.";
                $sentences[] = "The building features 24/7 security and maintenance services.";
                break;
            case 'penthouse':
                $sentences[] = "This penthouse offers unparalleled views of the surrounding area.";
                $sentences[] = "Enjoy your own private terrace perfect for entertaining guests.";
                break;
            case 'office':
                $sentences[] = "This office space is ideal for businesses looking for a professional environment.";
                $sentences[] = "The building offers meeting rooms and reception services.";
                break;
        }
        
        // Shuffle sentences for variety
        shuffle($sentences);
        
        // Select 5-7 random sentences
        $count = rand(5, min(7, count($sentences)));
        $selectedSentences = array_slice($sentences, 0, $count);
        
        return implode(' ', $selectedSentences);
    }
    
    /**
     * Get weighted random item
     */
    private function getRandomWeighted($items, $weights)
    {
        $totalWeight = array_sum($weights);
        $rand = mt_rand(1, $totalWeight);
        
        $currentWeight = 0;
        foreach ($items as $index => $item) {
            $currentWeight += $weights[$index];
            if ($rand <= $currentWeight) {
                return $item;
            }
        }
        
        return $items[0]; // Default fallback
    }
}

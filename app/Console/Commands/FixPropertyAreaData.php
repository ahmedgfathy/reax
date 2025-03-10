<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixPropertyAreaData extends Command
{
    protected $signature = 'properties:fix-area';
    protected $description = 'Fix property area data type issues';

    public function handle()
    {
        $this->info('Checking property area data...');

        // First, let's understand what type the 'area' column is
        $columnType = null;
        $isAreaNumeric = false;

        try {
            $columnInfo = DB::select("SHOW COLUMNS FROM properties WHERE Field = 'area'");
            if (!empty($columnInfo)) {
                $columnType = $columnInfo[0]->Type;
                $this->info("Area column type: {$columnType}");
                
                $isAreaNumeric = strpos(strtolower($columnType), 'int') !== false || 
                                strpos(strtolower($columnType), 'decimal') !== false || 
                                strpos(strtolower($columnType), 'float') !== false || 
                                strpos(strtolower($columnType), 'double') !== false;
            } else {
                $this->warn('Could not find area column in properties table');
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('Error checking column type: ' . $e->getMessage());
            return Command::FAILURE;
        }

        // If the area column is numeric, fix any string values
        if ($isAreaNumeric) {
            try {
                // Get properties with non-numeric area values
                $problematicIds = [];
                $properties = DB::table('properties')->get(['id', 'area']);
                
                foreach ($properties as $property) {
                    if (!is_numeric($property->area)) {
                        $problematicIds[] = $property->id;
                        $this->line("Property #{$property->id} has non-numeric area: {$property->area}");
                    }
                }
                
                if (empty($problematicIds)) {
                    $this->info('No problematic area values found!');
                    return Command::SUCCESS;
                }
                
                $this->info("Found " . count($problematicIds) . " properties with non-numeric area values");
                
                // Ask for confirmation before proceeding
                if (!$this->confirm('Do you want to fix these records?', true)) {
                    return Command::SUCCESS;
                }
                
                // Fix the properties by setting random area values
                foreach ($problematicIds as $id) {
                    $randomArea = rand(80, 500); // Random area between 80 and 500 square meters
                    DB::table('properties')
                        ->where('id', $id)
                        ->update(['area' => $randomArea]);
                    
                    $this->line("Updated property #{$id} with area: {$randomArea}");
                }
                
                $this->info('Successfully fixed all problematic area values!');
                
            } catch (\Exception $e) {
                $this->error('Error fixing area values: ' . $e->getMessage());
                return Command::FAILURE;
            }
        } else {
            $this->info('Area column is not numeric, no fixes needed');
        }

        return Command::SUCCESS;
    }
}

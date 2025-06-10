<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Services\AppwriteService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanDuplicatesAndSyncArea extends Command
{
    protected $signature = 'properties:clean-and-sync-area {--dry-run : Run without making changes}';
    protected $description = 'Remove duplicate properties and sync area data from Appwrite';

    private $appwriteService;

    public function __construct(AppwriteService $appwriteService)
    {
        parent::__construct();
        $this->appwriteService = $appwriteService;
    }

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->warn('Running in DRY-RUN mode - no changes will be made');
        }

        $this->info('Starting duplicate cleanup and area sync process...');

        // Step 1: Remove duplicate properties
        $this->cleanupDuplicates($isDryRun);

        // Step 2: Sync area data from Appwrite
        $this->syncAreaFromAppwrite($isDryRun);

        $this->info('Process completed successfully!');
        return Command::SUCCESS;
    }

    private function cleanupDuplicates($isDryRun = false)
    {
        $this->info('🔍 Finding duplicate properties...');

        // Find duplicates based on property details
        $duplicates = DB::table('properties')
            ->select('property_name', 'compound_name', 'total_price', 'total_area', DB::raw('COUNT(*) as count'), DB::raw('GROUP_CONCAT(id ORDER BY id ASC) as ids'))
            ->groupBy('property_name', 'compound_name', 'total_price', 'total_area')
            ->having('count', '>', 1)
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('✅ No duplicates found!');
            return;
        }

        $this->info("Found {$duplicates->count()} sets of duplicate properties");

        $totalToDelete = 0;
        foreach ($duplicates as $duplicate) {
            $ids = explode(',', $duplicate->ids);
            $totalToDelete += count($ids) - 1; // Keep the first one, delete the rest
            
            if ($isDryRun) {
                $this->line("Would delete duplicates for: {$duplicate->property_name} (IDs: " . implode(', ', array_slice($ids, 1)) . ")");
            }
        }

        if ($isDryRun) {
            $this->warn("DRY RUN: Would delete {$totalToDelete} duplicate properties");
            return;
        }

        if (!$this->confirm("This will delete {$totalToDelete} duplicate properties. Continue?", false)) {
            $this->warn('Operation cancelled by user');
            return;
        }

        $deleted = 0;
        foreach ($duplicates as $duplicate) {
            $ids = explode(',', $duplicate->ids);
            $keepId = array_shift($ids); // Keep the first (oldest) ID
            
            // Delete the duplicates
            foreach ($ids as $deleteId) {
                try {
                    Property::where('id', $deleteId)->delete();
                    $deleted++;
                    $this->line("Deleted duplicate property ID: {$deleteId}");
                } catch (\Exception $e) {
                    $this->error("Failed to delete property ID {$deleteId}: " . $e->getMessage());
                }
            }
        }

        $this->info("✅ Successfully deleted {$deleted} duplicate properties");
    }

    private function syncAreaFromAppwrite($isDryRun = false)
    {
        $this->info('🔄 Starting area data sync from Appwrite...');

        try {
            // Get properties that have appwrite_id but no area data
            $properties = Property::whereNotNull('appwrite_id')
                ->whereNull('area')
                ->get();

            if ($properties->isEmpty()) {
                $this->info('✅ All properties already have area data or no appwrite_id');
                return;
            }

            $this->info("Found {$properties->count()} properties to sync area data for");

            $synced = 0;
            $progressBar = $this->output->createProgressBar($properties->count());
            $progressBar->start();

            foreach ($properties as $property) {
                try {
                    // Fetch the property from Appwrite
                    $appwriteProperty = $this->appwriteService->getProperty($property->appwrite_id);
                    
                    if (!$appwriteProperty) {
                        $progressBar->advance();
                        continue;
                    }

                    // Convert Appwrite Document to array if needed
                    $propertyData = [];
                    if (is_array($appwriteProperty)) {
                        $propertyData = $appwriteProperty;
                    } elseif (is_object($appwriteProperty)) {
                        // Handle Appwrite Document object
                        if (method_exists($appwriteProperty, 'getArrayCopy')) {
                            $propertyData = $appwriteProperty->getArrayCopy();
                        } elseif (method_exists($appwriteProperty, 'toArray')) {
                            $propertyData = $appwriteProperty->toArray();
                        } else {
                            // Convert object to array manually
                            $propertyData = json_decode(json_encode($appwriteProperty), true);
                        }
                    }

                    // Extract area information from Appwrite property
                    $areaText = $this->extractAreaText($propertyData);

                    if ($areaText && !$isDryRun) {
                        $property->update(['area' => $areaText]);
                        $synced++;
                    }

                    if ($isDryRun && $areaText) {
                        Log::info("DRY RUN: Would update property {$property->id} with area: {$areaText}");
                        $synced++;
                    }

                } catch (\Exception $e) {
                    Log::error("Failed to sync area for property {$property->id}: " . $e->getMessage());
                }

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine();

            if ($isDryRun) {
                $this->warn("DRY RUN: Would sync area data for {$synced} properties");
            } else {
                $this->info("✅ Successfully synced area data for {$synced} properties");
            }

        } catch (\Exception $e) {
            $this->error('Error syncing area data: ' . $e->getMessage());
            Log::error('Area sync error: ' . $e->getMessage());
        }
    }

    private function extractAreaText($appwriteProperty)
    {
        $areas = [];

        // Check different possible area fields in Appwrite
        if (!empty($appwriteProperty['totalArea'])) {
            $areas[] = "Total: {$appwriteProperty['totalArea']}m²";
        }

        if (!empty($appwriteProperty['buildingArea'])) {
            $areas[] = "Building: {$appwriteProperty['buildingArea']}m²";
        }

        if (!empty($appwriteProperty['unitArea'])) {
            $areas[] = "Unit: {$appwriteProperty['unitArea']}m²";
        }

        if (!empty($appwriteProperty['landArea'])) {
            $areas[] = "Land: {$appwriteProperty['landArea']}m²";
        }

        if (!empty($appwriteProperty['gardenArea'])) {
            $areas[] = "Garden: {$appwriteProperty['gardenArea']}m²";
        }

        // If we have specific areas, combine them
        if (!empty($areas)) {
            return implode(', ', $areas);
        }

        // Fallback to any area field that might exist
        $fallbackFields = ['area', 'building', 'space', 'size'];
        foreach ($fallbackFields as $field) {
            if (!empty($appwriteProperty[$field]) && is_numeric($appwriteProperty[$field])) {
                return $appwriteProperty[$field] . 'm²';
            }
        }

        return null;
    }
}

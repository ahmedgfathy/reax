<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Services\AppwriteService;
use DB;

class CleanDuplicatesAndSyncAreaText extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:clean-and-sync-area {--dry-run : Show what would be done without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean duplicate properties and sync area text from Appwrite';

    private $appwriteService;

    public function __construct(AppwriteService $appwriteService)
    {
        parent::__construct();
        $this->appwriteService = $appwriteService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
        }
        
        $this->info('Starting properties cleanup and area text sync...');
        
        // Step 1: Remove duplicates
        $this->cleanDuplicateProperties($isDryRun);
        
        // Step 2: Sync area text from Appwrite
        $this->syncAreaTextFromAppwrite($isDryRun);
        
        $this->info('✅ Process completed!');
    }

    private function cleanDuplicateProperties($isDryRun)
    {
        $this->info('🧹 Step 1: Cleaning duplicate properties...');
        
        // Find duplicates based on appwrite_id (keep the oldest one)
        $duplicates = DB::select("
            SELECT appwrite_id, COUNT(*) as count, MIN(id) as keep_id, GROUP_CONCAT(id) as all_ids
            FROM properties 
            WHERE appwrite_id IS NOT NULL 
            GROUP BY appwrite_id 
            HAVING COUNT(*) > 1
        ");
        
        if (empty($duplicates)) {
            $this->info('No duplicate appwrite_ids found.');
        } else {
            $this->warn("Found " . count($duplicates) . " sets of duplicates based on appwrite_id");
            
            foreach ($duplicates as $duplicate) {
                $allIds = explode(',', $duplicate->all_ids);
                $idsToDelete = array_filter($allIds, function($id) use ($duplicate) {
                    return $id != $duplicate->keep_id;
                });
                
                $this->line("Appwrite ID {$duplicate->appwrite_id}: keeping ID {$duplicate->keep_id}, deleting IDs: " . implode(', ', $idsToDelete));
                
                if (!$isDryRun) {
                    Property::whereIn('id', $idsToDelete)->delete();
                }
            }
        }
        
        // Also check for potential name-based duplicates among migrated properties
        $nameDuplicates = DB::select("
            SELECT property_name, compound_name, COUNT(*) as count, MIN(id) as keep_id, GROUP_CONCAT(id) as all_ids
            FROM properties 
            WHERE appwrite_id IS NOT NULL 
              AND property_name IS NOT NULL 
              AND property_name != ''
            GROUP BY property_name, compound_name, total_price, total_area
            HAVING COUNT(*) > 1
        ");
        
        if (!empty($nameDuplicates)) {
            $this->warn("Found " . count($nameDuplicates) . " sets of duplicates based on property details");
            
            foreach ($nameDuplicates as $duplicate) {
                $allIds = explode(',', $duplicate->all_ids);
                $idsToDelete = array_filter($allIds, function($id) use ($duplicate) {
                    return $id != $duplicate->keep_id;
                });
                
                $this->line("Property '{$duplicate->property_name}' in '{$duplicate->compound_name}': keeping ID {$duplicate->keep_id}, deleting IDs: " . implode(', ', $idsToDelete));
                
                if (!$isDryRun) {
                    Property::whereIn('id', $idsToDelete)->delete();
                }
            }
        }
        
        $totalBefore = Property::count();
        $migratedBefore = Property::whereNotNull('appwrite_id')->count();
        
        $this->info("Current state: {$totalBefore} total properties, {$migratedBefore} migrated");
    }

    private function syncAreaTextFromAppwrite($isDryRun)
    {
        $this->info('🔄 Step 2: Syncing area text from Appwrite...');
        
        try {
            // Get all remote properties with area data
            $remoteProperties = $this->appwriteService->getAllProperties();
            $this->info("Found {$remoteProperties->total} properties in Appwrite");
            
            $synced = 0;
            $errors = 0;
            
            $progressBar = $this->output->createProgressBar($remoteProperties->total);
            
            foreach ($remoteProperties->documents as $remoteProperty) {
                $progressBar->advance();
                
                try {
                    // Find local property by appwrite_id
                    $localProperty = Property::where('appwrite_id', $remoteProperty['$id'])->first();
                    
                    if (!$localProperty) {
                        continue; // Skip if not found locally
                    }
                    
                    // Extract area text from remote property
                    $areaText = $this->extractAreaText($remoteProperty);
                    
                    if ($areaText && (!$localProperty->area_text || $localProperty->area_text !== $areaText)) {
                        if (!$isDryRun) {
                            $localProperty->area_text = $areaText;
                            $localProperty->save();
                        }
                        $synced++;
                    }
                    
                } catch (\Exception $e) {
                    $errors++;
                    $this->error("Error processing property {$remoteProperty['$id']}: " . $e->getMessage());
                }
            }
            
            $progressBar->finish();
            $this->newLine();
            
            if ($isDryRun) {
                $this->info("Would sync area_text for {$synced} properties");
            } else {
                $this->info("Synced area_text for {$synced} properties");
            }
            
            if ($errors > 0) {
                $this->warn("Encountered {$errors} errors during sync");
            }
            
        } catch (\Exception $e) {
            $this->error("Failed to connect to Appwrite: " . $e->getMessage());
        }
    }

    private function extractAreaText($remoteProperty)
    {
        // Extract area information and format as text
        $areaParts = [];
        
        if (isset($remoteProperty['totalArea']) && $remoteProperty['totalArea']) {
            $areaParts[] = "Total: {$remoteProperty['totalArea']}m²";
        }
        
        if (isset($remoteProperty['building']) && $remoteProperty['building']) {
            $areaParts[] = "Building: {$remoteProperty['building']}m²";
        }
        
        if (isset($remoteProperty['unitArea']) && $remoteProperty['unitArea']) {
            $areaParts[] = "Unit: {$remoteProperty['unitArea']}m²";
        }
        
        if (isset($remoteProperty['landArea']) && $remoteProperty['landArea']) {
            $areaParts[] = "Land: {$remoteProperty['landArea']}m²";
        }
        
        if (isset($remoteProperty['gardenArea']) && $remoteProperty['gardenArea']) {
            $areaParts[] = "Garden: {$remoteProperty['gardenArea']}m²";
        }
        
        return !empty($areaParts) ? implode(', ', $areaParts) : null;
    }
}

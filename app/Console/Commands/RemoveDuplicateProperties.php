<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Property;
use App\Models\PropertyMedia;

class RemoveDuplicateProperties extends Command
{
    protected $signature = 'properties:remove-duplicates {--dry-run : Run in dry-run mode without actually deleting}';
    protected $description = 'Remove duplicate properties from the database, keeping the oldest one';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->warn('Running in DRY-RUN mode - no data will be deleted');
        }

        $this->info('Finding duplicate properties...');

        // Find duplicates based on property_name and compound_name
        $duplicates = DB::select("
            SELECT property_name, compound_name, COUNT(*) as count, MIN(id) as keep_id, GROUP_CONCAT(id ORDER BY id) as all_ids
            FROM properties 
            GROUP BY property_name, compound_name 
            HAVING COUNT(*) > 1
            ORDER BY count DESC
        ");

        $totalDuplicateGroups = count($duplicates);
        $totalRecordsToDelete = 0;

        foreach ($duplicates as $duplicate) {
            $totalRecordsToDelete += ($duplicate->count - 1);
        }

        $this->info("Found {$totalDuplicateGroups} duplicate groups with {$totalRecordsToDelete} records to remove");

        if (!$this->confirm('Do you want to proceed with removing duplicates?')) {
            $this->info('Operation cancelled');
            return;
        }

        $progressBar = $this->output->createProgressBar($totalDuplicateGroups);
        $progressBar->start();

        $deletedCount = 0;
        $mediaCleanupCount = 0;

        foreach ($duplicates as $duplicate) {
            $allIds = explode(',', $duplicate->all_ids);
            $keepId = $duplicate->keep_id;
            $idsToDelete = array_filter($allIds, function($id) use ($keepId) {
                return $id != $keepId;
            });

            foreach ($idsToDelete as $idToDelete) {
                if (!$isDryRun) {
                    // First, handle associated media files
                    $mediaFiles = PropertyMedia::where('property_id', $idToDelete)->get();
                    foreach ($mediaFiles as $media) {
                        // Check if this media file exists for the property we're keeping
                        $existingMedia = PropertyMedia::where('property_id', $keepId)
                            ->where('media_path', $media->media_path)
                            ->first();
                        
                        if (!$existingMedia) {
                            // Move media association to the kept property
                            $media->update(['property_id' => $keepId]);
                        } else {
                            // Delete duplicate media record
                            $media->delete();
                            $mediaCleanupCount++;
                        }
                    }

                    // Delete the duplicate property
                    Property::where('id', $idToDelete)->delete();
                }
                $deletedCount++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        if ($isDryRun) {
            $this->info("DRY RUN RESULTS:");
            $this->info("Would delete {$deletedCount} duplicate properties");
            $this->info("Would cleanup {$mediaCleanupCount} duplicate media records");
        } else {
            $this->info("Successfully deleted {$deletedCount} duplicate properties");
            $this->info("Cleaned up {$mediaCleanupCount} duplicate media records");
            
            // Verify results
            $remainingProperties = Property::count();
            $this->info("Remaining properties: {$remainingProperties}");
        }

        return Command::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Models\PropertyMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CleanDuplicatesCommand extends Command
{
    protected $signature = 'clean:duplicates {--dry-run : Run in dry-run mode without deleting data} {--media : Also clean duplicate media files}';
    protected $description = 'Clean duplicate properties based on property_name, keeping ones with appwrite_id and optionally duplicate media files';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $cleanMedia = $this->option('media');
        
        if ($isDryRun) {
            $this->info('🔍 Running in DRY-RUN mode - no data will be deleted');
        }
        
        $this->info('🧹 Starting duplicate cleanup process...');
        
        // Clean duplicate properties
        $this->cleanDuplicateProperties($isDryRun);
        
        // Clean duplicate media if requested
        if ($cleanMedia) {
            $this->cleanDuplicateMedia($isDryRun);
        }
        
        $this->info('✅ Cleanup process completed!');
    }
    
    private function cleanDuplicateProperties($isDryRun)
    {
        $this->info('🔍 Finding duplicate properties by property name...');
        
        // Find duplicates by property_name where one has appwrite_id and another has NULL
        $duplicates = DB::select("
            SELECT property_name, COUNT(*) as count, 
                   GROUP_CONCAT(id ORDER BY (appwrite_id IS NOT NULL) DESC, created_at ASC) as ids,
                   GROUP_CONCAT(COALESCE(appwrite_id, 'NULL') ORDER BY (appwrite_id IS NOT NULL) DESC, created_at ASC) as appwrite_ids
            FROM properties 
            WHERE property_name IS NOT NULL 
            GROUP BY property_name 
            HAVING COUNT(*) > 1
            ORDER BY count DESC
        ");
        
        $this->info("Found " . count($duplicates) . " duplicate groups based on property_name");
        
        // Show summary of duplicates
        $totalDuplicateRows = 0;
        foreach ($duplicates as $duplicate) {
            $totalDuplicateRows += ($duplicate->count - 1); // -1 because we keep one
        }
        
        $this->info("Total duplicate rows to be removed: {$totalDuplicateRows}");
        
        if (count($duplicates) > 0) {
            $this->info("\nTop 10 most duplicated property names:");
            $topDuplicates = array_slice($duplicates, 0, 10);
            foreach ($topDuplicates as $duplicate) {
                $this->line("Property '{$duplicate->property_name}': {$duplicate->count} copies");
            }
        }
        
        if (!$this->confirm('Do you want to proceed with removing duplicates?')) {
            $this->info('Operation cancelled by user.');
            return;
        }
        
        $totalDeleted = 0;
        $progressBar = $this->output->createProgressBar(count($duplicates));
        $progressBar->start();
        
        foreach ($duplicates as $duplicate) {
            $ids = explode(',', $duplicate->ids);
            $appwriteIds = explode(',', $duplicate->appwrite_ids);
            
            // Keep the first one (which has appwrite_id due to our ORDER BY)
            $keepId = array_shift($ids);
            $deleteIds = $ids;
            
            if ($this->option('verbose') || $isDryRun) {
                $this->newLine();
                $this->line("Property Name: {$duplicate->property_name} ({$duplicate->count} copies)");
                $this->line("Appwrite IDs: " . implode(', ', $appwriteIds));
                $this->line("Keeping ID: {$keepId} (with appwrite_id), Deleting IDs: " . implode(', ', $deleteIds));
            }
            
            if (!$isDryRun && !empty($deleteIds)) {
                try {
                    DB::beginTransaction();
                    
                    // Only delete duplicate properties, do NOT delete media automatically
                    // Let media stay intact to avoid losing property images
                    $deletedProperties = Property::whereIn('id', $deleteIds)->delete();
                    
                    DB::commit();
                    
                    $totalDeleted += $deletedProperties;
                    
                    if ($this->option('verbose')) {
                        $this->line("Deleted {$deletedProperties} duplicate properties (media preserved)");
                    }
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->error("Error deleting property '{$duplicate->property_name}': " . $e->getMessage());
                }
            } else {
                $totalDeleted += count($deleteIds);
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        if ($isDryRun) {
            $this->info("Would delete {$totalDeleted} duplicate properties");
        } else {
            $this->info("✅ Deleted {$totalDeleted} duplicate properties");
        }
        
        // Show final count
        $finalCount = Property::count();
        $this->info("Final property count: {$finalCount}");
    }
    
    private function cleanDuplicateMedia($isDryRun)
    {
        $this->info('🔍 Finding duplicate media files...');
        
        // Find duplicate media files by file path
        $duplicateMedia = DB::select("
            SELECT file_path, COUNT(*) as count, GROUP_CONCAT(id ORDER BY created_at ASC) as ids
            FROM property_media 
            WHERE file_path IS NOT NULL 
            GROUP BY file_path 
            HAVING COUNT(*) > 1
        ");
        
        $this->info("Found " . count($duplicateMedia) . " duplicate media groups");
        
        $totalDeleted = 0;
        $filesDeleted = 0;
        
        foreach ($duplicateMedia as $media) {
            $ids = explode(',', $media->ids);
            $keepId = array_shift($ids); // Keep the oldest one
            $deleteIds = $ids;
            
            $this->line("Duplicate media: {$media->file_path}");
            $this->line("Keeping ID: {$keepId}, Deleting IDs: " . implode(', ', $deleteIds));
            
            if (!$isDryRun && !empty($deleteIds)) {
                try {
                    PropertyMedia::whereIn('id', $deleteIds)->delete();
                    $totalDeleted += count($deleteIds);
                } catch (\Exception $e) {
                    $this->error("Error deleting media records: " . $e->getMessage());
                }
            } else {
                $totalDeleted += count($deleteIds);
            }
        }
        
        // Find orphaned files in storage
        $this->info('🔍 Finding orphaned files in storage...');
        
        $storagePath = storage_path('app/public');
        if (is_dir($storagePath)) {
            $this->scanDirectoryForOrphans($storagePath, $isDryRun, $filesDeleted);
        }
        
        if ($isDryRun) {
            $this->info("Would delete {$totalDeleted} duplicate media records and {$filesDeleted} orphaned files");
        } else {
            $this->info("✅ Deleted {$totalDeleted} duplicate media records and {$filesDeleted} orphaned files");
        }
    }
    
    private function scanDirectoryForOrphans($directory, $isDryRun, &$filesDeleted)
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $file) {
            if ($file->isFile() && $this->isImageOrVideo($file->getPathname())) {
                $relativePath = str_replace(storage_path('app/public/'), '', $file->getPathname());
                
                // Check if this file is referenced in property_media
                $exists = PropertyMedia::where('file_path', $relativePath)
                    ->orWhere('file_path', 'storage/' . $relativePath)
                    ->exists();
                
                if (!$exists) {
                    $this->line("Orphaned file: {$relativePath}");
                    
                    if (!$isDryRun) {
                        try {
                            unlink($file->getPathname());
                            $filesDeleted++;
                        } catch (\Exception $e) {
                            $this->error("Error deleting file {$relativePath}: " . $e->getMessage());
                        }
                    } else {
                        $filesDeleted++;
                    }
                }
            }
        }
    }
    
    private function isImageOrVideo($filepath)
    {
        $extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'avi', 'mov', 'wmv', 'flv']);
    }
}

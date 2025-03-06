<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class CleanMigrationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrations:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up duplicate migrations, keeping only the newest version of each';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Scanning migrations directory...');
        
        // Get all migration files
        $migrationsPath = database_path('migrations');
        $files = File::files($migrationsPath);
        
        // Group files by their base name (ignoring timestamp prefixes)
        $fileGroups = [];
        
        foreach ($files as $file) {
            // Common migration filename format: YYYYMMDDHHMMSS_name.php
            $filename = $file->getFilename();
            
            // Try to match timestamp_name pattern
            preg_match('/^\d+_(.+)$/', $filename, $matches);
            
            $coreName = !empty($matches) ? $matches[1] : $filename;
            
            if (!isset($fileGroups[$coreName])) {
                $fileGroups[$coreName] = [];
            }
            
            $fileGroups[$coreName][] = $file;
        }
        
        $deletedCount = 0;
        $keptCount = 0;
        
        // Process each group of files
        foreach ($fileGroups as $coreName => $fileList) {
            if (count($fileList) <= 1) {
                $keptCount++;
                $this->info("Keeping single file: {$fileList[0]->getFilename()}");
                continue; // Skip if there's only one file
            }
            
            // Sort by creation date (newest first)
            usort($fileList, function (SplFileInfo $a, SplFileInfo $b) {
                return filemtime($b->getPathname()) - filemtime($a->getPathname());
            });
            
            // Keep the newest file
            $newestFile = $fileList[0];
            $this->info("Keeping newest file: {$newestFile->getFilename()} (modified: " . date('Y-m-d H:i:s', filemtime($newestFile->getPathname())) . ")");
            $keptCount++;
            
            // Delete the rest (older duplicates)
            foreach (array_slice($fileList, 1) as $file) {
                $this->warn("Deleting older duplicate: {$file->getFilename()} (modified: " . date('Y-m-d H:i:s', filemtime($file->getPathname())) . ")");
                File::delete($file->getPathname());
                $deletedCount++;
            }
        }
        
        $this->newLine();
        $this->info("Migration cleanup complete!");
        $this->info("Files kept: {$keptCount}");
        $this->info("Files deleted: {$deletedCount}");
        
        return Command::SUCCESS;
    }
}

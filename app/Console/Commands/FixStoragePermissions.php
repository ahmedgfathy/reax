<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FixStoragePermissions extends Command
{
    protected $signature = 'storage:fix-permissions';
    protected $description = 'Fix storage directory permissions';

    public function handle()
    {
        $this->info('Fixing storage directory permissions...');

        // Create avatars directory if it doesn't exist
        $avatarsPath = storage_path('app/public/avatars');
        if (!File::isDirectory($avatarsPath)) {
            File::makeDirectory($avatarsPath, 0755, true);
            $this->info('Created avatars directory');
        }

        // Ensure storage directory is writable
        $storagePath = storage_path();
        chmod($storagePath, 0755);
        $this->info('Set permissions on storage directory');

        // Ensure public storage directory is writable
        $publicStoragePath = storage_path('app/public');
        chmod($publicStoragePath, 0755);
        $this->info('Set permissions on public storage directory');

        // Ensure bootstrap/cache is writable
        $cachePath = base_path('bootstrap/cache');
        if (File::isDirectory($cachePath)) {
            chmod($cachePath, 0755);
            $this->info('Set permissions on bootstrap/cache directory');
        }

        $this->info('Creating symbolic links...');
        $this->call('storage:link');

        $this->info('All done!');
    }
}

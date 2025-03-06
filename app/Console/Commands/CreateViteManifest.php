<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateViteManifest extends Command
{
    protected $signature = 'vite:manifest';
    protected $description = 'Create an empty Vite manifest file for development';

    public function handle()
    {
        $buildDir = public_path('build');
        
        if (!File::isDirectory($buildDir)) {
            File::makeDirectory($buildDir, 0755, true);
        }
        
        $manifestPath = $buildDir . '/manifest.json';
        $manifest = [
            'resources/css/app.css' => [
                'file' => 'assets/app.css',
                'src' => 'resources/css/app.css',
                'isEntry' => true
            ],
            'resources/js/app.js' => [
                'file' => 'assets/app.js',
                'src' => 'resources/js/app.js',
                'isEntry' => true
            ]
        ];
        
        File::put($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT));
        
        // Create empty asset files
        if (!File::isDirectory($buildDir . '/assets')) {
            File::makeDirectory($buildDir . '/assets', 0755, true);
        }
        
        File::put($buildDir . '/assets/app.css', '/* Placeholder CSS */');
        File::put($buildDir . '/assets/app.js', '/* Placeholder JS */');
        
        $this->info('Empty Vite manifest and asset files created successfully.');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users first since they're needed by other models
        $this->call([
            \Database\Seeders\UserSeeder::class,
        ]);
        
        // Create properties before leads (since leads may reference properties)
        $this->call([
            PropertySeeder::class,
        ]);
        
        // Create leads with activity logs
        $this->call([
            LeadSeeder::class,
        ]);
        
        // Create reports last since they may reference both leads and properties
        $this->call([
            ReportSeeder::class,
        ]);
    }
}

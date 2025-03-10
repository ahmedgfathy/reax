<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if projects already exist
        if (DB::table('projects')->count() > 0) {
            $this->command->info('Projects table already has records. Skipping seeding.');
            return;
        }

        // Insert sample projects
        DB::table('projects')->insert([
            [
                'name' => 'Green Heights',
                'description' => 'A modern eco-friendly residential complex',
                'location' => 'New Cairo',
                'developer' => 'EcoBuilders',
                'status' => 'under construction',
                'launch_date' => '2024-06-01',
                'completion_date' => '2026-12-31',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Business Hub',
                'description' => 'Premier office spaces in downtown',
                'location' => 'Downtown',
                'developer' => 'Commercial Developers Inc',
                'status' => 'planned',
                'launch_date' => '2024-09-15',
                'completion_date' => '2027-03-30',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Riviera Residences',
                'description' => 'Luxury waterfront apartments',
                'location' => 'North Coast',
                'developer' => 'Luxury Living',
                'status' => 'completed',
                'launch_date' => '2022-01-15',
                'completion_date' => '2024-01-30',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        $this->command->info('Projects table seeded!');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $this->call([
                // Core system tables
                RolesTableSeeder::class,
                PermissionsTableSeeder::class,
                CompaniesTableSeeder::class,
                TeamsTableSeeder::class,
                UsersTableSeeder::class,
                
                // Module tables
                ProjectsTableSeeder::class,
                PropertiesTableSeeder::class,
                LeadSeeder::class,
                
                // Additional seeders
                ReportSeeder::class
            ]);
            
            // Create default data if needed
            if (DB::table('users')->count() === 0) {
                \App\Models\User::factory(5)->create();
            }
            
            if (DB::table('properties')->count() === 0) {
                \App\Models\Property::factory(20)->create();
            }
            
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}

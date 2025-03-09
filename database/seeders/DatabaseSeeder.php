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
            // Run seeders in correct order
            $this->call([
                CompaniesTableSeeder::class,
                TeamsTableSeeder::class,
                UsersTableSeeder::class,
                RolesTableSeeder::class,
                PermissionsTableSeeder::class,
                RolePermissionsTableSeeder::class,
                UserRolesTableSeeder::class,
                PropertiesTableSeeder::class,
                LeadsTableSeeder::class,
                ActivityLogsTableSeeder::class,
            ]);

            // Create additional fake data if needed
            if (DB::table('companies')->count() === 0) {
                \App\Models\Company::factory(5)->create();
            }
            if (DB::table('users')->count() === 0) {
                \App\Models\User::factory(10)->create();
            }
            if (DB::table('properties')->count() === 0) {
                \App\Models\Property::factory(50)->create();
            }

        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}

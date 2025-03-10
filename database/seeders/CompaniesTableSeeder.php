<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if companies already exist
        if (DB::table('companies')->count() > 0) {
            $this->command->info('Companies table already has records. Skipping seeding.');
            return;
        }

        // Check which columns exist in the companies table
        $columns = Schema::getColumnListing('companies');
        $hasDescription = in_array('description', $columns);
        $hasStatus = in_array('status', $columns);

        // Prepare base data for all companies
        $companies = [
            [
                'name' => 'Company A',
                'slug' => 'company-a',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Company B',
                'slug' => 'company-b',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Company C',
                'slug' => 'company-c',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        // Add description if the column exists
        if ($hasDescription) {
            $companies[0]['description'] = 'A real estate company focusing on residential properties';
            $companies[1]['description'] = 'Commercial real estate specialists';
            $companies[2]['description'] = 'Luxury property management';
        }

        // Add status if the column exists
        if ($hasStatus) {
            $companies[0]['status'] = 'approved';
            $companies[1]['status'] = 'approved';
            $companies[2]['status'] = 'pending';
        }

        // Insert the companies with the appropriate columns
        DB::table('companies')->insert($companies);

        $this->command->info('Companies table seeded successfully!');
    }

    /**
     * Check if a table exists in the database
     */
    private function tableExists($table)
    {
        try {
            DB::connection()->getPdo()->query("SELECT 1 FROM {$table} LIMIT 1");
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

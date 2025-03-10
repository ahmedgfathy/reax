<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if teams already exist
        if (DB::table('teams')->count() > 0) {
            $this->command->info('Teams table already has records. Skipping seeding.');
            return;
        }

        // Get company IDs if available
        $companyIds = DB::table('companies')->pluck('id')->toArray();
        $defaultCompanyId = count($companyIds) > 0 ? $companyIds[0] : null;
        
        // Check which columns exist in the teams table
        $columns = Schema::getColumnListing('teams');
        $hasDescription = in_array('description', $columns);
        
        // Prepare base team data
        $teams = [
            [
                'name' => 'Sales Team',
                'company_id' => $defaultCompanyId,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Marketing Team',
                'company_id' => $defaultCompanyId,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Operations Team',
                'company_id' => count($companyIds) > 1 ? $companyIds[1] : $defaultCompanyId,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        // Add description if the column exists
        if ($hasDescription) {
            $teams[0]['description'] = 'Handles property sales';
            $teams[1]['description'] = 'Manages property marketing';
            $teams[2]['description'] = 'Manages daily operations';
        }

        // Insert the teams with the appropriate columns
        DB::table('teams')->insert($teams);

        $this->command->info('Teams table seeded successfully!');
    }
}

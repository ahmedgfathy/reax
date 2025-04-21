<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        
        try {
            // Create base data first
            $this->call([
                CompanySeeder::class,
                AdminUserSeeder::class,
                LeadClassificationSeeder::class,
                UserSeeder::class,
            ]);

            // Wait briefly to ensure primary data is committed
            sleep(1);

            // Create dependent data
            $this->call([
                PropertySeeder::class,
                LeadSeeder::class,
                OpportunitySeeder::class
            ]);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error($e->getMessage());
        }
    }
}

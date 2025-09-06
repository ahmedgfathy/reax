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
            $this->call([
                CompanySeeder::class,
                RoleSeeder::class,
                UserSeeder::class,
                TeamSeeder::class,
                LeadClassificationSeeder::class,
                PropertySeeder::class,
                LeadSeeder::class,
                OpportunitySeeder::class,
                EventSeeder::class,
            ]);
            
            DB::commit();
            $this->command->info('Database seeded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding database: ' . $e->getMessage());
            throw $e;
        }
    }
}

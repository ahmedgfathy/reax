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
            // Run CompanySeeder first and get the created company
            $company = $this->call(CompanySeeder::class);
            
            // Pass company to UserSeeder
            $this->call(UserSeeder::class, false, ['company' => $company]);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error($e->getMessage());
        }
    }
}

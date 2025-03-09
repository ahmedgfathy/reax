<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaniesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('companies')->insert([
            ['name' => 'Company A', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Company B', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

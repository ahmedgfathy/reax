<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('teams')->insert([
            ['name' => 'Team A', 'company_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Team B', 'company_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

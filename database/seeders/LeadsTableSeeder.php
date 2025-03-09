<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('leads')->insert([
            [
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'email' => 'alice@example.com',
                'phone' => '1234567890',
                'interested_property' => 'Modern Apartment',
                'budget' => 1600000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'first_name' => 'Bob',
                'last_name' => 'Smith',
                'email' => 'bob@example.com',
                'phone' => '0987654321',
                'interested_property' => 'Luxury Villa',
                'budget' => 5500000,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}

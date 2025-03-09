<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertiesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('properties')->insert([
            [
                'title' => 'Modern Apartment in New Cairo',
                'name' => 'Modern Apartment',
                'location' => 'New Cairo',
                'price' => 1500000,
                'currency' => 'USD',
                'unit_for' => 'sale',
                'rooms' => 3,
                'bathrooms' => 2,
                'area' => 120,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Luxury Villa in Giza',
                'name' => 'Luxury Villa',
                'location' => 'Giza',
                'price' => 5000000,
                'currency' => 'USD',
                'unit_for' => 'rent',
                'rooms' => 5,
                'bathrooms' => 4,
                'area' => 300,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}

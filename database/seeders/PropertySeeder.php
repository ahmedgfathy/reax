<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();
        $users = User::all();

        // Only create if we have less than 50 properties
        if (Property::count() < 50) {
            $remainingCount = 50 - Property::count();
            
            for ($i = 0; $i < $remainingCount; $i++) {
                Property::create([
                    'company_id' => $company->id,
                    'property_name' => fake()->words(3, true),
                    'compound_name' => fake()->company(),
                    'unit_for' => fake()->randomElement(['sale', 'rent']),
                    'type' => fake()->randomElement(['apartment', 'villa', 'duplex', 'penthouse']),
                    'total_area' => fake()->numberBetween(80, 500),
                    'unit_area' => fake()->numberBetween(60, 400),
                    'rooms' => fake()->numberBetween(1, 6),
                    'bathrooms' => fake()->numberBetween(1, 4),
                    'total_price' => fake()->numberBetween(500000, 5000000),
                    'price_per_meter' => fake()->numberBetween(5000, 20000),
                    'currency' => 'EGP',
                    'status' => fake()->randomElement(['available', 'sold', 'reserved']),
                    'handler_id' => $users->random()->id,
                    'is_published' => true,
                ]);
            }
        }
    }
}

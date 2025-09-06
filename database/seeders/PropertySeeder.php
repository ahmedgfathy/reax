<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use App\Models\Property;
use App\Models\Company;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    protected $propertyTypes = ['apartment', 'villa', 'duplex', 'penthouse', 'studio', 'office', 'retail'];
    protected $features = ['pool', 'security', 'parking', 'built-in kitchen', 'gym', 'balcony', 'elevator'];
    protected $amenities = ['security', 'playground', 'shopping', 'schools', 'mosque', 'hospital'];
    protected $compounds = ['Mountain View', 'Palm Hills', 'Madinaty', 'El Rehab', 'Hyde Park'];

    public function run()
    {
        $company = Company::first();
        $users = User::where('company_id', $company->id)->get();
        $teams = Team::all();
        
        for ($i = 0; $i < 50; $i++) {
            $type = $this->propertyTypes[array_rand($this->propertyTypes)];
            $unitFor = fake()->randomElement(['sale', 'rent']);
            $totalArea = fake()->numberBetween(80, 500);
            $pricePerMeter = fake()->numberBetween(15000, 35000);
            
            Property::create([
                'company_id' => $company->id,
                'team_id' => $teams->random()->id,
                'handler_id' => $users->random()->id,
                'property_name' => "Modern {$type} in " . fake()->randomElement($this->compounds),
                'property_number' => 'PRO' . str_pad($i + 10000000, 8, '0', STR_PAD_LEFT),
                'compound_name' => fake()->randomElement($this->compounds),
                'unit_for' => $unitFor,
                'type' => $type,
                'category' => fake()->randomElement(['residential', 'commercial']),
                'status' => fake()->randomElement(['available', 'sold', 'rented', 'reserved']),
                'location_type' => fake()->randomElement(['inside', 'outside']),
                'phase' => fake()->numberBetween(1, 5),
                'building' => fake()->buildingNumber(),
                'floor' => fake()->numberBetween(1, 20),
                'unit_no' => fake()->unique()->numberBetween(1, 1000),
                'total_area' => $totalArea,
                'unit_area' => $totalArea * 0.9,
                'garden_area' => fake()->optional(0.3)->numberBetween(20, 100),
                'rooms' => fake()->numberBetween(2, 6),
                'bathrooms' => fake()->numberBetween(1, 4),
                'features' => fake()->randomElements($this->features, fake()->numberBetween(2, 5)),
                'amenities' => fake()->randomElements($this->amenities, fake()->numberBetween(2, 4)),
                'total_price' => $totalArea * $pricePerMeter,
                'price_per_meter' => $pricePerMeter,
                'currency' => 'EGP',
                'description' => fake()->paragraphs(3, true),
                'is_featured' => fake()->boolean(20),
                'is_published' => true,
                'finished' => fake()->boolean(90),
            ]);
        }
    }
}

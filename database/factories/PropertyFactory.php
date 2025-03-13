<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use App\Models\Company;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition()
    {
        $totalArea = fake()->numberBetween(80, 500);
        $pricePerMeter = fake()->numberBetween(5000, 20000);

        return [
            'company_id' => Company::factory(),
            'property_name' => fake()->words(3, true),
            'compound_name' => fake()->company(),
            'property_number' => fake()->unique()->bothify('P-####'),
            'unit_no' => fake()->bothify('U-###'),
            'unit_for' => fake()->randomElement(['sale', 'rent']),
            'type' => fake()->randomElement(['apartment', 'villa', 'duplex', 'penthouse', 'studio', 'office', 'retail', 'land']),
            'phase' => fake()->numberBetween(1, 5),
            'building' => fake()->bothify('B-###'),
            'floor' => fake()->numberBetween(1, 20),
            'finished' => fake()->boolean(80),
            'total_area' => $totalArea,
            'unit_area' => $totalArea * 0.9,
            'rooms' => fake()->numberBetween(1, 6),
            'bathrooms' => fake()->numberBetween(1, 4),
            'amenities' => fake()->randomElements(['pool', 'gym', 'parking', 'security', 'garden'], fake()->numberBetween(1, 5)),
            'location_type' => fake()->randomElement(['inside', 'outside']),
            'category' => fake()->randomElement(['residential', 'commercial', 'administrative']),
            'status' => fake()->randomElement(['available', 'sold', 'rented', 'reserved']),
            'total_price' => $totalArea * $pricePerMeter,
            'price_per_meter' => $pricePerMeter,
            'currency' => 'EGP',
            'property_offered_by' => fake()->randomElement(['owner', 'agent', 'company']),
            'owner_name' => fake()->name(),
            'owner_mobile' => fake()->phoneNumber(),
            'handler_id' => User::factory(),
            'project_id' => fake()->boolean(70) ? Project::factory() : null,  // Make project optional
            'description' => fake()->paragraphs(3, true),
            'features' => fake()->randomElements(['central_ac', 'built_in_kitchen', 'balcony', 'view'], fake()->numberBetween(1, 4)),
            'is_featured' => fake()->boolean(20),
            'is_published' => true,
        ];
    }
}

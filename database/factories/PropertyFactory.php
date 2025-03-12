<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition()
    {
        $types = ['apartment', 'villa', 'duplex', 'penthouse', 'studio', 'office', 'retail', 'land'];
        $unitFor = ['sale', 'rent'];
        $categories = ['residential', 'commercial', 'administrative'];
        $statuses = ['available', 'sold', 'rented', 'reserved'];
        $currencies = ['EGP', 'USD', 'EUR'];
        $offeredBy = ['owner', 'agent', 'company'];

        return [
            'property_name' => $this->faker->words(3, true),
            'compound_name' => $this->faker->company,
            'property_number' => $this->faker->buildingNumber,
            'unit_no' => $this->faker->numberBetween(1, 1000),
            'unit_for' => $this->faker->randomElement($unitFor),
            'type' => $this->faker->randomElement($types),
            'phase' => 'Phase ' . $this->faker->numberBetween(1, 5),
            'building' => $this->faker->buildingNumber,
            'floor' => $this->faker->numberBetween(1, 20),
            'finished' => $this->faker->boolean,
            'total_area' => $this->faker->numberBetween(80, 500),
            'unit_area' => $this->faker->numberBetween(60, 400),
            'rooms' => $this->faker->numberBetween(1, 6),
            'bathrooms' => $this->faker->numberBetween(1, 4),
            'location_type' => $this->faker->randomElement(['inside', 'outside']),
            'category' => $this->faker->randomElement($categories),
            'status' => $this->faker->randomElement($statuses),
            'total_price' => $this->faker->numberBetween(500000, 5000000),
            'currency' => $this->faker->randomElement($currencies),
            'property_offered_by' => $this->faker->randomElement($offeredBy),
            'owner_name' => $this->faker->name,
            'owner_mobile' => $this->faker->phoneNumber,
            'description' => $this->faker->paragraphs(2, true),
            'is_featured' => $this->faker->boolean(20),
        ];
    }
}

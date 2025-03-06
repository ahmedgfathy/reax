<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition()
    {
        $propertyTypes = ['apartment', 'villa', 'townhouse', 'penthouse', 'studio', 'office', 'retail', 'land'];
        $categories = ['residential', 'commercial', 'industrial', 'land'];
        $statuses = ['available', 'sold', 'reserved', 'rented'];
        $finishedOptions = ['yes', 'no', 'semi'];
        $locationTypes = ['inside', 'outside'];
        $unitFor = ['rent', 'sale'];
        $currencies = ['USD', 'EGP', 'EUR', 'GBP'];
        
        // Get a random user to be the handler
        $userId = User::inRandomOrder()->first()?->id;
        if (!$userId) {
            $userId = User::factory()->create()->id;
        }
        
        // Generate location values
        $compounds = ['Green Valley', 'Palm Hills', 'Mountain View', 'Hyde Park', 'Madinaty', 'Uptown Cairo', 'New Cairo'];
        $compound = $this->faker->randomElement($compounds);
        $propertyType = $this->faker->randomElement($propertyTypes);
        
        return [
            'title' => ucfirst($propertyType) . ' in ' . $compound,
            'location' => $this->faker->randomElement(['New Cairo', 'October', 'Sheikh Zayed', 'El Shorouk', 'Madinaty', 'El Rehab']),
            'type' => $this->faker->randomElement($propertyTypes),
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->numberBetween(100000, 10000000),
            'rooms' => $this->faker->numberBetween(1, 6),
            'bathrooms' => $this->faker->numberBetween(1, 5),
            'area' => $this->faker->numberBetween(80, 500),
            'status' => $this->faker->randomElement($statuses),
            'is_featured' => $this->faker->boolean(30),
            'owner_name' => $this->faker->name(),
            'owner_mobile' => $this->faker->phoneNumber(),
            // Remove address field since it doesn't exist in the database
            //'address' => $this->faker->address(),
            
            // Add required fields from PropertyController
            'compound_name' => $compound,
            'unit_for' => $this->faker->randomElement($unitFor),
            'category' => $this->faker->randomElement($categories),
            'currency' => $this->faker->randomElement($currencies),
            'property_offered_by' => $this->faker->randomElement(['owner', 'agent', 'developer']),
            'handler_id' => $userId,
            
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }
}

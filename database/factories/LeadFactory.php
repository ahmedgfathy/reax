<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\User;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition()
    {
        $statuses = ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost'];
        $sources = ['website', 'referral', 'social media', 'direct', 'advertisement', 'other'];
        
        // Make sure we have at least one property and one user in the database
        $propertyId = Property::inRandomOrder()->first()?->id;
        $userId = User::inRandomOrder()->first()?->id;
        
        // If we found no property or user, make sure we don't cause errors
        if (!$propertyId) {
            // Create a property if none exists
            $propertyId = Property::factory()->create()->id;
        }
        
        if (!$userId) {
            // Create a user if none exists
            $userId = User::factory()->create()->id;
        }
        
        // Based on the actual leads table structure
        // Use property_id instead of property_interest
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement($statuses),
            'source' => $this->faker->randomElement($sources),
            // Use property_id instead of property_interest
            'property_id' => $this->faker->boolean(80) ? $propertyId : null,
            'budget' => $this->faker->numberBetween(50000, 2000000),
            'notes' => $this->faker->boolean(70) ? $this->faker->paragraph() : null,
            'assigned_to' => $this->faker->boolean(80) ? $userId : null,
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }
}

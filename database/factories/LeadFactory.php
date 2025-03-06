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
        $sources = ['website', 'referral', 'social media', 'direct', 'advertisement', 'property portal', 'walk-in'];
        
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
        
        $status = $this->faker->randomElement($statuses);
        
        // Determine lead class based on status
        $leadClass = 'C'; // Default to cold lead
        if (in_array($status, ['proposal', 'negotiation', 'won'])) {
            $leadClass = 'A'; // Hot lead
        } elseif ($status === 'qualified') {
            $leadClass = 'B'; // Warm lead
        }
        
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'status' => $status,
            'lead_status' => $status, // For compatibility with both field names
            'source' => $this->faker->randomElement($sources),
            'lead_source' => $this->faker->randomElement($sources), // For compatibility with both field names
            // Remove property reference from factory definition
            //'property_interest' => $this->faker->boolean(80) ? $propertyId : null,
            'budget' => $this->faker->numberBetween(500000, 5000000),
            'notes' => $this->faker->boolean(70) ? $this->faker->paragraph() : null,
            'description' => $this->faker->boolean(60) ? $this->faker->paragraph() : null,
            'assigned_to' => $this->faker->boolean(80) ? $userId : null,
            'last_modified_by' => $userId,
            'last_follow_up' => $this->faker->boolean(50) ? $this->faker->dateTimeBetween('-2 months', '+1 month') : null,
            'lead_class' => $leadClass,
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }
}

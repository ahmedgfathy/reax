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
        
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement($statuses),
            'source' => $this->faker->randomElement($sources),
            'property_interest' => $propertyId,
            'budget' => $this->faker->numberBetween(50000, 2000000),
            'notes' => $this->faker->paragraph(),
            'assigned_to' => $userId,
        ];
    }
}

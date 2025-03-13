<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\User;
use App\Models\Company;
use App\Models\Property;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'mobile' => fake()->phoneNumber(),
            'status' => fake()->randomElement(['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost']),
            'source' => fake()->randomElement(['website', 'referral', 'social_media', 'direct', 'agent', 'other']),
            'budget' => fake()->numberBetween(500000, 5000000),
            'property_interest' => Property::factory(),
            'assigned_to' => User::factory(),
            'team_id' => fake()->boolean(70) ? Team::factory() : null,
            'notes' => fake()->paragraph(),
            'lead_class' => fake()->randomElement(['hot', 'warm', 'cold']),
            'last_follow_up' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}

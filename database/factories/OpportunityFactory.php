<?php

namespace Database\Factories;

use App\Models\Opportunity;
use App\Models\Company;
use App\Models\Lead;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpportunityFactory extends Factory
{
    protected $model = Opportunity::class;

    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'title' => fake()->catchPhrase(),
            'lead_id' => Lead::factory(),
            'property_id' => Property::factory(),
            'assigned_to' => User::factory(),
            'status' => fake()->randomElement(['pending', 'negotiation', 'won', 'lost']),
            'value' => fake()->numberBetween(100000, 5000000),
            'probability' => fake()->randomElement([25, 50, 75, 100]),
            'expected_close_date' => fake()->dateTimeBetween('now', '+6 months'),
            'description' => fake()->paragraph(),
            'notes' => fake()->paragraph(),
            'source' => fake()->randomElement(['website', 'referral', 'direct', 'agent']),
            'stage' => fake()->randomElement(['initial', 'qualified', 'proposal', 'negotiation']),
            'type' => fake()->randomElement(['sale', 'rent']),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'last_activity_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'last_modified_by' => User::factory(),
        ];
    }
}

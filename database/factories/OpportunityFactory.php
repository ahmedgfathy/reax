<?php

namespace Database\Factories;

use App\Models\Opportunity;
use App\Models\Property;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpportunityFactory extends Factory
{
    protected $model = Opportunity::class;

    public function definition()
    {
        return [
            'name' => 'Deal ' . fake()->company(),
            'status' => fake()->randomElement(['initial', 'qualified', 'proposal', 'negotiation', 'closed_won', 'closed_lost']),
            'value' => fake()->numberBetween(500000, 5000000),
            'close_date' => fake()->dateTimeBetween('now', '+6 months'),
            'property_id' => Property::factory(),
            'lead_id' => Lead::factory(),
            'assigned_to' => User::factory(),
            'notes' => fake()->paragraph(),
        ];
    }
}

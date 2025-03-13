<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use App\Models\Lead;
use App\Models\Property;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        // Create start date between now and 2 weeks from now
        $startDate = fake()->dateTimeBetween('now', '+2 weeks');
        // Create end date 1-3 hours after start date
        $endDate = (clone $startDate)->modify('+' . fake()->numberBetween(1, 3) . ' hours');
        
        return [
            'company_id' => Company::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'event_type' => fake()->randomElement(['meeting', 'call', 'site_visit', 'follow_up', 'other']),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => fake()->randomElement(['scheduled', 'in_progress', 'completed', 'cancelled']),
            'lead_id' => Lead::factory(),
            'property_id' => Property::factory(),
            'created_by' => User::factory(),
            'attendees' => fake()->randomElements(['user_1', 'user_2', 'user_3', 'user_4'], fake()->numberBetween(1, 4)),
            'outcome' => fake()->optional()->paragraph(),
        ];
    }
}

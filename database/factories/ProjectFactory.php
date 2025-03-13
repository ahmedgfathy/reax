<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'name' => fake()->unique()->words(3, true),
            'code' => fake()->unique()->bothify('PRJ-####'),
            'description' => fake()->paragraph(),
            'location' => fake()->address(),
            'developer' => fake()->company(),
            'status' => fake()->randomElement(['planning', 'ongoing', 'completed', 'on_hold', 'cancelled']),
            'launch_date' => fake()->dateTimeBetween('-1 year', '+6 months'),
            'completion_date' => fake()->dateTimeBetween('+6 months', '+2 years'),
            'featured_image' => 'projects/' . fake()->uuid() . '.jpg',
            'amenities' => fake()->randomElements([
                'Swimming Pool',
                'Gym',
                'Security',
                'Parking',
                'Kids Area',
                'Green Areas',
                'Commercial Area',
                'Club House'
            ], fake()->numberBetween(3, 8))
        ];
    }
}

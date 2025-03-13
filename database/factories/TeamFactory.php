<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'name' => fake()->words(2, true) . ' Team',
            'code' => fake()->unique()->bothify('TM-####'),
            'leader_id' => User::factory(),
            'department_id' => fake()->boolean(70) ? Department::factory() : null  // Make department optional
        ];
    }
}

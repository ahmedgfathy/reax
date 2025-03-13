<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'name' => fake()->unique()->words(2, true) . ' Department',
            'code' => fake()->unique()->bothify('DEP-####'),
            'description' => fake()->sentence(),
            'manager_id' => User::factory()
            // Remove is_active since it doesn't exist in schema
        ];
    }
}

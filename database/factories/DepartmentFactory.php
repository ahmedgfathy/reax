<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'name' => $this->faker->words(2, true) . ' Department',
            'code' => 'DEP-' . $this->faker->numberBetween(1000, 9999),
            'description' => $this->faker->sentence(),
            'manager_name' => $this->faker->name(),
            'manager_phone' => $this->faker->phoneNumber(),
            'manager_email' => $this->faker->safeEmail(),
            'is_active' => $this->faker->boolean(80),
            'notes' => $this->faker->paragraph(),
            'parent_id' => null // Will be set manually if needed
        ];
    }
}

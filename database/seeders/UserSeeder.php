<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();

        // Create admin user if doesn't exist
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'is_active' => true
            ]
        );

        // Create some regular users if we have less than 5
        if (User::count() < 6) {
            $positions = ['Sales Agent', 'Property Manager', 'Sales Manager', 'Customer Service'];
            
            foreach (range(1, 5) as $i) {
                User::updateOrCreate(
                    ['email' => fake()->unique()->safeEmail()],
                    [
                        'name' => fake()->name(),
                        'password' => Hash::make('password'),
                        'phone' => fake()->phoneNumber(),
                        'mobile' => fake()->phoneNumber(),
                        'position' => fake()->randomElement($positions),
                        'address' => fake()->address(),
                        'is_active' => true
                    ]
                );
            }
        }
    }
}

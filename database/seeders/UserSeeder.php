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

        if (!$company) {
            $company = Company::create([
                'name' => 'Default Company',
                'slug' => 'default-company',
                'email' => 'company@example.com',
                'is_active' => true
            ]);
        }

        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@realestate.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('Admin@123'),
                'is_admin' => true,
                'is_company_admin' => true,
                'is_active' => true,
                'company_id' => $company->id,
                'position' => 'System Administrator'
            ]
        );

        // Set company owner
        $company->owner_id = $admin->id;
        $company->save();

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

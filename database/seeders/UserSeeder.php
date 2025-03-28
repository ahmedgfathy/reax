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
        $company = Company::where('email', 'company@example.com')->first();
        
        if (!$company) {
            $this->command->error('Company not found. Please run CompanySeeder first.');
            return;
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

        // Update company owner
        $company->owner_id = $admin->id;
        $company->save();

        // Create regular users
        $positions = ['Sales Agent', 'Property Manager', 'Sales Manager', 'Customer Service'];
        
        foreach (range(1, 5) as $i) {
            User::updateOrCreate(
                ['email' => "user{$i}@realestate.com"],
                [
                    'name' => "Test User {$i}",
                    'password' => Hash::make('User@123'),
                    'phone' => fake()->phoneNumber(),
                    'mobile' => fake()->phoneNumber(),
                    'position' => $positions[array_rand($positions)],
                    'address' => fake()->address(),
                    'is_active' => true,
                    'company_id' => $company->id
                ]
            );
        }
    }
}

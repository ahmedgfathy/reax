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
        try {
            $company = Company::where('email', 'company@example.com')->firstOrFail();
            
            // Create admin user with company_id
            $admin = User::create([
                'name' => 'System Admin',
                'email' => 'admin@realestate.com',
                'password' => Hash::make('Admin@123'),
                'is_admin' => true,
                'is_company_admin' => true,
                'is_active' => true,
                'company_id' => $company->id,
                'position' => 'System Administrator'
            ]);

            // Update company owner
            $company->owner_id = $admin->id;
            $company->save();

            $this->command->info('Admin user created successfully.');

            // Create regular users
            $positions = ['Sales Agent', 'Property Manager', 'Sales Manager', 'Customer Service'];
            
            foreach (range(1, 5) as $i) {
                User::create([
                    'name' => "Test User {$i}",
                    'email' => "user{$i}@realestate.com",
                    'password' => Hash::make('User@123'),
                    'phone' => fake()->phoneNumber(),
                    'mobile' => fake()->phoneNumber(),
                    'position' => $positions[array_rand($positions)],
                    'address' => fake()->address(),
                    'is_active' => true,
                    'company_id' => $company->id
                ]);
            }

            $this->command->info('Regular users created successfully.');

        } catch (\Exception $e) {
            $this->command->error('Error seeding users: ' . $e->getMessage());
        }
    }
}

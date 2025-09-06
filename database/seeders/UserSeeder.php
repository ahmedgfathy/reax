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
            $company = Company::first();
            
            if (!$company) {
                throw new \Exception('Company not found. Please ensure CompanySeeder runs first.');
            }

            // Create admin user
            User::firstOrCreate(
                ['email' => 'admin@reax.com'],
                [
                    'name' => 'System Admin',
                    'password' => Hash::make('password'),
                    'company_id' => $company->id,
                    'role' => 'admin',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            // Create test users with different roles
            $users = [
                [
                    'email' => 'manager@reax.com',
                    'name' => 'Manager User',
                    'role' => 'manager'
                ],
                [
                    'email' => 'agent@reax.com',
                    'name' => 'Agent User',
                    'role' => 'agent'
                ],
                [
                    'email' => 'employee@reax.com',
                    'name' => 'Employee User',
                    'role' => 'employee'
                ]
            ];

            foreach ($users as $userData) {
                User::firstOrCreate(
                    ['email' => $userData['email']],
                    [
                        'name' => $userData['name'],
                        'password' => Hash::make('password'),
                        'company_id' => $company->id,
                        'role' => $userData['role'],
                        'is_active' => true,
                        'email_verified_at' => now(),
                    ]
                );
            }

            $this->command->info('Users seeded successfully.');
        } catch (\Exception $e) {
            $this->command->error('Error seeding users: ' . $e->getMessage());
            throw $e;
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();
        if (!$company) {
            $this->command->error('No company found. Please run CompanySeeder first.');
            return;
        }

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@reax.com',
            'password' => Hash::make('password'),
            'company_id' => $company->id,
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create some test users
        $roles = ['agent', 'manager'];
        foreach ($roles as $role) {
            User::create([
                'name' => ucfirst($role) . ' User',
                'email' => $role . '@reax.com',
                'password' => Hash::make('password'),
                'company_id' => $company->id,
                'role' => $role,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }
    }
}

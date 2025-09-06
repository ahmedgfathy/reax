<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    protected $signature = 'admin:create-super-admin';
    protected $description = 'Create the super admin user with full system access';

    public function handle()
    {
        // Create default company first
        $company = Company::create([
            'name' => 'System Administration',
            'slug' => 'system-administration',
            'email' => 'admin@example.com',
            'is_active' => true,
        ]);

        // Create super admin role
        $role = Role::create([
            'name' => 'Super Administrator',
            'slug' => 'super-admin',
            'description' => 'Full system access',
            'company_id' => $company->id,
            'permissions' => ['*'], // All permissions
        ]);

        // Create super admin user
        $user = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'company_id' => $company->id,
            'role_id' => $role->id,
            'is_admin' => true,
            'is_active' => true,
        ]);

        // Update company owner
        $company->update(['owner_id' => $user->id]);

        $this->info('Super Admin created successfully!');
        $this->info('Email: admin@example.com');
        $this->info('Password: password');
        $this->info('Please change the password after first login.');
    }
}

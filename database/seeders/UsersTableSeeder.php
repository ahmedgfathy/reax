<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Check if users already exist
        if (DB::table('users')->count() > 0) {
            $this->command->info('Users table already has records. Skipping seeding.');
            return;
        }
        
        // Get company IDs if available
        $companies = DB::table('companies')->pluck('id', 'name')->toArray();
        
        // Get team IDs if available
        $teams = DB::table('teams')->pluck('id', 'name')->toArray();
        
        // Get role IDs if available
        $roles = DB::table('roles')->pluck('id', 'name')->toArray();
        
        // Check which columns exist in the users table
        $columns = Schema::getColumnListing('users');
        $hasRoleId = in_array('role_id', $columns);
        $hasCompanyId = in_array('company_id', $columns);
        $hasTeamId = in_array('team_id', $columns);
        $hasIsActive = in_array('is_active', $columns);
        $hasIsAdmin = in_array('is_admin', $columns);
        $hasIsCompanyAdmin = in_array('is_company_admin', $columns);
        
        // Insert admin user
        $adminUser = [
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => \Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        // Add additional fields if they exist
        if ($hasIsAdmin) $adminUser['is_admin'] = true;
        if ($hasIsCompanyAdmin) $adminUser['is_company_admin'] = true;
        if ($hasIsActive) $adminUser['is_active'] = true;
        if ($hasRoleId && isset($roles['Admin'])) $adminUser['role_id'] = $roles['Admin'];
        if ($hasCompanyId && !empty($companies)) $adminUser['company_id'] = reset($companies);
        
        DB::table('users')->insert($adminUser);
        
        // Insert regular user
        $regularUser = [
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => \Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        // Add additional fields if they exist
        if ($hasIsAdmin) $regularUser['is_admin'] = false;
        if ($hasIsCompanyAdmin) $regularUser['is_company_admin'] = false;
        if ($hasIsActive) $regularUser['is_active'] = true;
        if ($hasRoleId && isset($roles['User'])) $regularUser['role_id'] = $roles['User'];
        if ($hasCompanyId && !empty($companies)) $regularUser['company_id'] = reset($companies);
        if ($hasTeamId && !empty($teams)) $regularUser['team_id'] = reset($teams);
        
        DB::table('users')->insert($regularUser);
        
        // Create manager user if role exists
        if (isset($roles['Manager'])) {
            $managerUser = [
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => \Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Add additional fields if they exist
            if ($hasIsAdmin) $managerUser['is_admin'] = false;
            if ($hasIsCompanyAdmin) $managerUser['is_company_admin'] = true;
            if ($hasIsActive) $managerUser['is_active'] = true;
            if ($hasRoleId) $managerUser['role_id'] = $roles['Manager'];
            if ($hasCompanyId && !empty($companies)) $managerUser['company_id'] = reset($companies);
            if ($hasTeamId && !empty($teams)) $managerUser['team_id'] = array_key_exists('Sales Team', $teams) ? $teams['Sales Team'] : reset($teams);
            
            DB::table('users')->insert($managerUser);
        }

        $this->command->info('Users table seeded successfully!');
    }
}

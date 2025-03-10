<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // Check if roles already exist
        if (DB::table('roles')->count() > 0) {
            $this->command->info('Roles table already has records. Skipping seeding.');
            return;
        }

        // Check what columns exist in the roles table
        $columns = Schema::getColumnListing('roles');
        $hasSlug = in_array('slug', $columns);
        $hasDescription = in_array('description', $columns);
        $hasIsSystem = in_array('is_system', $columns);

        // Define base roles
        $roles = [
            [
                'name' => 'Admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'User',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Manager',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Team Lead',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Employee',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Add additional fields if they exist in the schema
        foreach ($roles as $key => $role) {
            if ($hasSlug) {
                $roles[$key]['slug'] = Str::slug($role['name']);
            }
            
            if ($hasDescription) {
                $roles[$key]['description'] = $this->getRoleDescription($role['name']);
            }
            
            if ($hasIsSystem) {
                $roles[$key]['is_system'] = true;
            }
        }

        DB::table('roles')->insert($roles);
        
        $this->command->info('Roles table seeded!');
    }

    /**
     * Get a description for a role
     */
    private function getRoleDescription($roleName)
    {
        $descriptions = [
            'Admin' => 'Full system access with all permissions',
            'User' => 'Basic user with limited permissions',
            'Manager' => 'Can manage team members and view all leads',
            'Team Lead' => 'Can manage team activities and assign tasks',
            'Employee' => 'Standard employee with task-specific permissions'
        ];

        return $descriptions[$roleName] ?? 'Role in the system';
    }
}

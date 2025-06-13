<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\Permission;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles = [
            [
                'name' => 'Administration Profile',
                'display_name' => 'Administration',
                'description' => 'Full system administration access with all permissions',
                'level' => 'administration',
                'is_active' => true,
                'permissions' => [
                    'properties.view', 'properties.create', 'properties.edit', 'properties.delete',
                    'users.view', 'users.create', 'users.edit', 'users.delete',
                    'leads.view', 'leads.create', 'leads.edit', 'leads.delete',
                    'companies.view', 'companies.create', 'companies.edit', 'companies.delete',
                    'reports.view', 'reports.export'
                ]
            ],
            [
                'name' => 'Manager Profile',
                'display_name' => 'Manager',
                'description' => 'Manager level access with team management capabilities',
                'level' => 'manager',
                'is_active' => true,
                'permissions' => [
                    'properties.view', 'properties.create', 'properties.edit',
                    'users.view', 'users.edit',
                    'leads.view', 'leads.create', 'leads.edit', 'leads.delete',
                    'reports.view', 'reports.export'
                ]
            ],
            [
                'name' => 'Team Leader Profile',
                'display_name' => 'Team Leader',
                'description' => 'Team leader with limited team management and full operational access',
                'level' => 'team_leader',
                'is_active' => true,
                'permissions' => [
                    'properties.view', 'properties.create', 'properties.edit',
                    'leads.view', 'leads.create', 'leads.edit', 'leads.delete',
                    'reports.view'
                ]
            ],
            [
                'name' => 'Employee Profile',
                'display_name' => 'Employee',
                'description' => 'Basic employee access with limited permissions',
                'level' => 'employee',
                'is_active' => true,
                'permissions' => [
                    'properties.view',
                    'leads.view', 'leads.create', 'leads.edit'
                ]
            ]
        ];

        foreach ($profiles as $profileData) {
            $permissions = $profileData['permissions'];
            unset($profileData['permissions']);

            $profile = Profile::create($profileData);

            // Attach permissions to profile
            foreach ($permissions as $permissionSlug) {
                $permission = Permission::where('slug', $permissionSlug)->first();
                if ($permission) {
                    $profile->permissions()->attach($permission->id);
                }
            }

            $this->command->info("Created profile: {$profile->display_name}");
        }
    }
}

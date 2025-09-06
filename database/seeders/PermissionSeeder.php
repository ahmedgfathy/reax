<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $companies = Company::all();
        
        foreach ($companies as $company) {
            $permissions = [
                // Properties Module
                [
                    'name' => 'View Properties',
                    'slug' => 'properties.view',
                    'description' => 'Can view properties list and details',
                    'module' => 'properties',
                    'actions' => ['read']
                ],
                [
                    'name' => 'Create Properties',
                    'slug' => 'properties.create',
                    'description' => 'Can create new properties',
                    'module' => 'properties',
                    'actions' => ['create']
                ],
                [
                    'name' => 'Edit Properties',
                    'slug' => 'properties.edit',
                    'description' => 'Can edit existing properties',
                    'module' => 'properties',
                    'actions' => ['update']
                ],
                [
                    'name' => 'Delete Properties',
                    'slug' => 'properties.delete',
                    'description' => 'Can delete properties',
                    'module' => 'properties',
                    'actions' => ['delete']
                ],
                
                // Leads Module
                [
                    'name' => 'View Leads',
                    'slug' => 'leads.view',
                    'description' => 'Can view leads list and details',
                    'module' => 'leads',
                    'actions' => ['read']
                ],
                [
                    'name' => 'Create Leads',
                    'slug' => 'leads.create',
                    'description' => 'Can create new leads',
                    'module' => 'leads',
                    'actions' => ['create']
                ],
                [
                    'name' => 'Edit Leads',
                    'slug' => 'leads.edit',
                    'description' => 'Can edit existing leads',
                    'module' => 'leads',
                    'actions' => ['update']
                ],
                [
                    'name' => 'Delete Leads',
                    'slug' => 'leads.delete',
                    'description' => 'Can delete leads',
                    'module' => 'leads',
                    'actions' => ['delete']
                ],
                
                // Reports Module
                [
                    'name' => 'View Reports',
                    'slug' => 'reports.view',
                    'description' => 'Can view and generate reports',
                    'module' => 'reports',
                    'actions' => ['read']
                ],
                [
                    'name' => 'Create Reports',
                    'slug' => 'reports.create',
                    'description' => 'Can create custom reports',
                    'module' => 'reports',
                    'actions' => ['create']
                ],
                [
                    'name' => 'Export Reports',
                    'slug' => 'reports.export',
                    'description' => 'Can export reports to various formats',
                    'module' => 'reports',
                    'actions' => ['read']
                ],
                
                // Administration Module
                [
                    'name' => 'Manage Users',
                    'slug' => 'administration.users',
                    'description' => 'Can manage user accounts and roles',
                    'module' => 'administration',
                    'actions' => ['create', 'read', 'update', 'delete']
                ],
                [
                    'name' => 'Manage Profiles',
                    'slug' => 'administration.profiles',
                    'description' => 'Can create and manage user profiles',
                    'module' => 'administration',
                    'actions' => ['create', 'read', 'update', 'delete']
                ],
                [
                    'name' => 'Manage Permissions',
                    'slug' => 'administration.permissions',
                    'description' => 'Can manage system permissions',
                    'module' => 'administration',
                    'actions' => ['create', 'read', 'update', 'delete']
                ],
                
                // Teams Module
                [
                    'name' => 'View Teams',
                    'slug' => 'teams.view',
                    'description' => 'Can view team information',
                    'module' => 'teams',
                    'actions' => ['read']
                ],
                [
                    'name' => 'Manage Teams',
                    'slug' => 'teams.manage',
                    'description' => 'Can create and manage teams',
                    'module' => 'teams',
                    'actions' => ['create', 'read', 'update', 'delete']
                ],
                
                // Opportunities Module
                [
                    'name' => 'View Opportunities',
                    'slug' => 'opportunities.view',
                    'description' => 'Can view opportunities',
                    'module' => 'opportunities',
                    'actions' => ['read']
                ],
                [
                    'name' => 'Manage Opportunities',
                    'slug' => 'opportunities.manage',
                    'description' => 'Can create and manage opportunities',
                    'module' => 'opportunities',
                    'actions' => ['create', 'read', 'update', 'delete']
                ],
            ];
            
            foreach ($permissions as $permissionData) {
                Permission::updateOrCreate(
                    [
                        'slug' => $permissionData['slug'],
                        'company_id' => $company->id
                    ],
                    array_merge($permissionData, [
                        'company_id' => $company->id,
                        'role_id' => 1 // Default to admin role
                    ])
                );
            }
        }
        
        $this->command->info('Permissions seeded successfully!');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        // Check if permissions already exist
        if (DB::table('permissions')->count() > 0) {
            $this->command->info('Permissions table already has records. Skipping seeding.');
            return;
        }

        // Check what columns exist in the permissions table
        $columns = Schema::getColumnListing('permissions');
        $hasSlug = in_array('slug', $columns);
        $hasDescription = in_array('description', $columns);
        $hasIsSystem = in_array('is_system', $columns);

        // Define base permissions
        $permissionNames = [
            'view_dashboard',
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'view_leads',
            'create_leads',
            'edit_leads',
            'delete_leads',
            'view_properties',
            'create_properties',
            'edit_properties',
            'delete_properties',
            'import_leads',
            'export_leads',
            'view_reports',
            'create_reports',
        ];

        $permissions = [];
        foreach ($permissionNames as $name) {
            $permission = [
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            if ($hasSlug) {
                $permission['slug'] = Str::slug($name);
            }
            
            if ($hasDescription) {
                $permission['description'] = $this->getPermissionDescription($name);
            }
            
            if ($hasIsSystem) {
                $permission['is_system'] = true;
            }
            
            $permissions[] = $permission;
        }

        DB::table('permissions')->insert($permissions);
        
        $this->command->info('Permissions table seeded!');
    }

    /**
     * Get a description for a permission
     */
    private function getPermissionDescription($permission)
    {
        $descriptions = [
            'view_dashboard' => 'Access to view dashboard',
            'view_users' => 'Access to view user list',
            'create_users' => 'Ability to create new users',
            'edit_users' => 'Ability to edit existing users',
            'delete_users' => 'Ability to delete users',
            'view_roles' => 'Access to view roles',
            'create_roles' => 'Ability to create new roles',
            'edit_roles' => 'Ability to edit existing roles',
            'delete_roles' => 'Ability to delete roles',
            'view_leads' => 'Access to view leads',
            'create_leads' => 'Ability to create new leads',
            'edit_leads' => 'Ability to edit existing leads',
            'delete_leads' => 'Ability to delete leads',
            'view_properties' => 'Access to view properties',
            'create_properties' => 'Ability to create new properties',
            'edit_properties' => 'Ability to edit existing properties',
            'delete_properties' => 'Ability to delete properties',
            'import_leads' => 'Ability to import leads from files',
            'export_leads' => 'Ability to export leads to files',
            'view_reports' => 'Access to view reports',
            'create_reports' => 'Ability to create new reports',
        ];

        return $descriptions[$permission] ?? 'System permission';
    }
}

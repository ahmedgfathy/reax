<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'System Administrator'
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Team Manager'
            ],
            [
                'name' => 'employee',
                'display_name' => 'Employee',
                'description' => 'Regular Employee'
            ]
        ];

        foreach ($roles as $role) {
            Role::withTrashed()->updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('permissions')->insert([
            ['name' => 'view_users', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'edit_users', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

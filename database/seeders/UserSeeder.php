<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we already have users
        $userCount = User::count();
        if ($userCount > 0) {
            $this->command->info("Skipping user seeding, {$userCount} users already exist.");
            return;
        }
        
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Create regular user
        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // Create additional random users
        User::factory()
            ->count(10)
            ->create();

        $this->command->info('Created 12 user records (including admin and regular test accounts).');
    }
}

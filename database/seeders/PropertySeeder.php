<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we already have properties
        $propertyCount = Property::count();
        if ($propertyCount > 0) {
            $this->command->info("Skipping property seeding, {$propertyCount} properties already exist.");
            return;
        }
        
        // Create 30 properties
        Property::factory()
            ->count(30)
            ->create();

        $this->command->info('Created 30 property records.');
    }
}

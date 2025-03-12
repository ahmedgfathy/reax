<?php

namespace Database\Seeders;

use App\Models\Opportunity;
use App\Models\Lead;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class OpportunitySeeder extends Seeder
{
    public function run()
    {
        // Check if we have required related data
        $leads = Lead::all();
        $properties = Property::all();
        $users = User::all();

        if ($leads->isEmpty() || $properties->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Missing required related data for opportunities.');
            return;
        }

        $statuses = ['initial', 'qualified', 'proposal', 'negotiation', 'closed_won', 'closed_lost'];

        // Create 30 opportunities
        for ($i = 0; $i < 30; $i++) {
            Opportunity::create([
                'name' => 'Deal ' . fake()->company(),
                'status' => fake()->randomElement($statuses),
                'value' => fake()->numberBetween(500000, 5000000),
                'close_date' => fake()->dateTimeBetween('now', '+6 months'),
                'property_id' => $properties->random()->id,
                'lead_id' => $leads->random()->id,
                'assigned_to' => $users->random()->id,
                'notes' => fake()->paragraph(),
            ]);
        }

        $this->command->info('Created 30 opportunities.');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Opportunity;
use App\Models\User;
use App\Models\Company;
use App\Models\Property;
use App\Models\Lead;
use Illuminate\Database\Seeder;

class OpportunitySeeder extends Seeder
{
    public function run()
    {
        try {
            $company = Company::first();
            if (!$company) {
                throw new \Exception('Company not found. Please run CompanySeeder first.');
            }

            $users = User::where('company_id', $company->id)->get();
            if ($users->isEmpty()) {
                throw new \Exception('No users found. Please run UserSeeder first.');
            }

            $properties = Property::all();
            if ($properties->isEmpty()) {
                throw new \Exception('No properties found. Please run PropertySeeder first.');
            }

            $leads = Lead::all();
            if ($leads->isEmpty()) {
                throw new \Exception('No leads found. Please run LeadSeeder first.');
            }

            for ($i = 0; $i < 20; $i++) {
                Opportunity::create([
                    'company_id' => $company->id,
                    'assigned_to' => $users->random()->id,  // Changed from handler_id to assigned_to
                    'lead_id' => $leads->random()->id,
                    'property_id' => $properties->random()->id,
                    'title' => fake()->sentence(),
                    'status' => fake()->randomElement(['open', 'qualified', 'proposal', 'negotiation', 'won', 'lost']),
                    'stage' => fake()->randomElement(['initial', 'meeting', 'proposal', 'negotiation', 'closing']),
                    'value' => fake()->numberBetween(500000, 5000000),
                    'probability' => fake()->randomElement([25, 50, 75, 100]),
                    'expected_close_date' => fake()->dateTimeBetween('now', '+3 months'),
                    'description' => fake()->paragraph(),
                    'notes' => fake()->paragraph(),
                    'priority' => fake()->randomElement(['low', 'medium', 'high'])
                ]);
            }

            $this->command->info('Opportunities seeded successfully.');
        } catch (\Exception $e) {
            $this->command->error('Error seeding opportunities: ' . $e->getMessage());
            throw $e;
        }
    }
}

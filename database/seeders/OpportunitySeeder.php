<?php

namespace Database\Seeders;

use App\Models\Opportunity;
use App\Models\Company;
use App\Models\Lead;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class OpportunitySeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();
        $users = User::all();
        $leads = Lead::all();
        $properties = Property::all();

        foreach(range(1, 20) as $i) {
            Opportunity::create([
                'company_id' => $company->id,
                'title' => fake()->catchPhrase(),
                'lead_id' => $leads->random()->id,
                'property_id' => $properties->random()->id,
                'assigned_to' => $users->random()->id,
                'status' => fake()->randomElement(['pending', 'negotiation', 'won', 'lost']),
                'value' => fake()->numberBetween(100000, 5000000),
                'probability' => fake()->randomElement([25, 50, 75, 100]),
                'expected_close_date' => fake()->dateTimeBetween('now', '+6 months'),
                'description' => fake()->paragraph(),
                'notes' => fake()->paragraph(),
                'source' => fake()->randomElement(['website', 'referral', 'direct', 'agent']),
                'stage' => fake()->randomElement(['initial', 'qualified', 'proposal', 'negotiation']),
                'type' => fake()->randomElement(['sale', 'rent']),
                'priority' => fake()->randomElement(['low', 'medium', 'high']),
                'last_activity_at' => now(),
                'last_modified_by' => $users->random()->id,
            ]);
        }
    }
}

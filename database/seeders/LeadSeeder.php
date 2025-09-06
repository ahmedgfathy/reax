<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\User;
use App\Models\Company;
use App\Models\LeadClassification;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();
        $users = User::where('company_id', $company->id)->get();
        $classifications = LeadClassification::all();
        
        for ($i = 0; $i < 50; $i++) {
            Lead::create([
                'company_id' => $company->id,
                'assigned_to' => $users->random()->id,
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => '01' . fake()->numberBetween(0, 2) . fake()->numberBetween(10000000, 99999999),
                'mobile' => '01' . fake()->numberBetween(0, 2) . fake()->numberBetween(10000000, 99999999),
                'status' => fake()->randomElement(['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost']),
                'lead_source' => fake()->randomElement(['website', 'referral', 'social', 'direct', 'property portal']),
                'lead_type' => fake()->randomElement(['hot', 'warm', 'cold']),
                'lead_classification_id' => $classifications->random()->id,
                'budget' => fake()->numberBetween(500000, 5000000),
                'requirements' => fake()->paragraph(),
                'notes' => fake()->optional()->paragraph(),
                'last_contact' => fake()->dateTimeBetween('-3 months', 'now'),
                'next_follow_up' => fake()->optional()->dateTimeBetween('now', '+1 month'),
            ]);
        }
    }
}

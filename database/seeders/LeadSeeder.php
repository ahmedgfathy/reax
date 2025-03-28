<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
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

            for ($i = 0; $i < 30; $i++) {
                Lead::create([
                    'company_id' => $company->id,
                    'assigned_to' => $users->random()->id,
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'email' => fake()->unique()->safeEmail(),
                    'phone' => fake()->phoneNumber(),
                    'mobile' => fake()->phoneNumber(),
                    'status' => fake()->randomElement(['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost']),
                    'lead_source' => fake()->randomElement(['website', 'referral', 'social', 'direct']),
                    'lead_type' => fake()->randomElement(['hot', 'warm', 'cold']),
                    'budget' => fake()->numberBetween(500000, 5000000),
                    'requirements' => fake()->paragraphs(1, true),
                    'notes' => fake()->paragraphs(1, true),
                    'last_contact' => fake()->dateTimeBetween('-3 months', 'now'),
                    'next_follow_up' => fake()->dateTimeBetween('now', '+1 month')
                ]);
            }

            $this->command->info('Leads seeded successfully.');
        } catch (\Exception $e) {
            $this->command->error('Error seeding leads: ' . $e->getMessage());
            throw $e;
        }
    }
}

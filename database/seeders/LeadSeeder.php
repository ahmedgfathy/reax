<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();
        $users = User::all();

        // Only create if we have less than 50 leads
        if (Lead::count() < 50) {
            $remainingCount = 50 - Lead::count();
            
            for ($i = 0; $i < $remainingCount; $i++) {
                Lead::create([
                    'company_id' => $company->id,
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'email' => fake()->email(),
                    'phone' => fake()->phoneNumber(),
                    'mobile' => fake()->phoneNumber(),
                    'status' => fake()->randomElement(['new', 'contacted', 'qualified', 'proposal']),
                    'source' => fake()->randomElement(['website', 'referral', 'social_media', 'direct']),
                    'budget' => fake()->numberBetween(500000, 5000000),
                    'assigned_to' => $users->random()->id,
                    'lead_class' => fake()->randomElement(['hot', 'warm', 'cold']),
                ]);
            }
        }
    }
}

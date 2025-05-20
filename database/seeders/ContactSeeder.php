<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Company;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run()
    {
        $companies = Company::all();
        $types = ['client', 'prospect', 'partner'];

        for ($i = 0; $i < 50; $i++) {
            Contact::create([
                'company_id' => $companies->random()->id,
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'mobile' => fake()->phoneNumber(),
                'type' => $types[array_rand($types)],
                'position' => fake()->jobTitle(),
                'notes' => fake()->paragraph(),
                'metadata' => [
                    'source' => fake()->randomElement(['website', 'referral', 'social_media', 'direct']),
                    'interests' => fake()->randomElements(['residential', 'commercial', 'investment', 'rental'], rand(1, 3)),
                    'preferred_location' => fake()->city(),
                    'budget_range' => fake()->numberBetween(500000, 5000000)
                ],
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run()
    {
        try {
            $company = Company::first();
            if (!$company) {
                throw new \Exception('No company found. Please run CompanySeeder first.');
            }

            $users = User::where('company_id', $company->id)->get();
            if ($users->isEmpty()) {
                throw new \Exception('No users found. Please run UserSeeder first.');
            }

            // Fake image URLs for seeding
            $fakeImageUrls = [
                'https://source.unsplash.com/800x600/?apartment',
                'https://source.unsplash.com/800x600/?house',
                'https://source.unsplash.com/800x600/?villa',
                'https://source.unsplash.com/800x600/?property',
                'https://source.unsplash.com/800x600/?real-estate'
            ];

            for ($i = 0; $i < 50; $i++) {
                $property = Property::create([
                    'company_id' => $company->id,
                    'handler_id' => $users->random()->id,
                    'property_name' => fake()->words(3, true) . ' Property',
                    'compound_name' => fake()->company(),
                    'unit_for' => fake()->randomElement(['sale', 'rent']),
                    'type' => fake()->randomElement(['apartment', 'villa', 'duplex', 'penthouse', 'studio']),
                    'category' => fake()->randomElement(['residential', 'commercial', 'administrative']),
                    'status' => 'available',
                    'total_area' => fake()->numberBetween(80, 500),
                    'total_price' => fake()->numberBetween(500000, 5000000),
                    'rooms' => fake()->numberBetween(1, 6),
                    'bathrooms' => fake()->numberBetween(1, 4),
                    'currency' => 'EGP',
                    'owner_name' => fake()->name(),
                    'owner_mobile' => fake()->phoneNumber(),
                    'location_type' => fake()->randomElement(['inside', 'outside']),
                    'is_published' => true,
                    'features' => json_encode(['balcony', 'pool']),
                    'amenities' => json_encode(['gym', 'spa'])
                ]);

                // Create media record for each property
                $property->media()->create([
                    'type' => 'image',
                    'file_path' => $fakeImageUrls[array_rand($fakeImageUrls)],
                    'is_featured' => true
                ]);

                // Add 2-4 additional images
                for ($j = 0; $j < rand(2, 4); $j++) {
                    $property->media()->create([
                        'type' => 'image',
                        'file_path' => $fakeImageUrls[array_rand($fakeImageUrls)],
                        'is_featured' => false
                    ]);
                }
            }

            $this->command->info('Properties seeded successfully.');
        } catch (\Exception $e) {
            $this->command->error('Error seeding properties: ' . $e->getMessage());
            throw $e;
        }
    }
}

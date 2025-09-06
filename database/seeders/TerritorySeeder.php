<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Territory;
use App\Models\Company;
use App\Models\Team;
use App\Models\User;

class TerritorySeeder extends Seeder
{
    public function run(): void
    {
        // Get first company for seeding
        $company = Company::first();
        if (!$company) {
            $this->command->info('No companies found. Please seed companies first.');
            return;
        }

        // Check if territories already exist
        if (Territory::count() > 0) {
            $this->command->info('Territories already exist. Skipping seeding.');
            return;
        }

        // Get first team for seeding
        $team = Team::first();
        if (!$team) {
            $this->command->info('No teams found. Creating sample team.');
            $team = Team::create([
                'name' => 'Sales Team',
                'company_id' => $company->id,
                'description' => 'Main sales team'
            ]);
        }

        // Get a manager user
        $manager = User::where('role', 'manager')->first() ?? User::first();

        $territories = [
            [
                'name' => 'Downtown District',
                'code' => 'DWN',
                'description' => 'Central business district and downtown area',
                'type' => 'geographic',
                'company_id' => $company->id,
                'team_id' => $team->id,
                'manager_id' => $manager->id,
                'geographic_boundaries' => [
                    'type' => 'polygon',
                    'coordinates' => [[[40.7128, -74.0060], [40.7589, -73.9851], [40.7505, -73.9934], [40.7128, -74.0060]]]
                ],
                'postal_codes' => ['10001', '10002', '10003'],
                'cities' => ['New York'],
                'regions' => ['Manhattan'],
                'target_demographics' => ['business_professionals', 'young_professionals'],
                'customer_segments' => ['enterprise', 'small_business'],
                'target_revenue' => 500000.00,
                'is_active' => true,
                'priority_level' => 5 // High priority
            ],
            [
                'name' => 'Residential North',
                'code' => 'RN',
                'description' => 'Northern residential neighborhoods',
                'type' => 'geographic',
                'company_id' => $company->id,
                'team_id' => $team->id,
                'manager_id' => $manager->id,
                'geographic_boundaries' => [
                    'type' => 'polygon',
                    'coordinates' => [[[40.7831, -73.9712], [40.8176, -73.9442], [40.8000, -73.9200], [40.7831, -73.9712]]]
                ],
                'postal_codes' => ['10025', '10026', '10027'],
                'cities' => ['New York'],
                'regions' => ['Upper Manhattan'],
                'target_demographics' => ['families', 'retirees'],
                'customer_segments' => ['residential', 'luxury'],
                'target_revenue' => 350000.00,
                'is_active' => true,
                'priority_level' => 3 // Medium priority
            ],
            [
                'name' => 'Corporate South',
                'code' => 'CS',
                'description' => 'Southern corporate and industrial zone',
                'type' => 'industry',
                'company_id' => $company->id,
                'team_id' => $team->id,
                'manager_id' => $manager->id,
                'geographic_boundaries' => [
                    'type' => 'polygon',
                    'coordinates' => [[[40.6892, -74.0445], [40.7282, -74.0776], [40.7000, -74.1000], [40.6892, -74.0445]]]
                ],
                'postal_codes' => ['10004', '10005', '10006'],
                'cities' => ['New York'],
                'regions' => ['Lower Manhattan', 'Financial District'],
                'target_demographics' => ['corporate_executives', 'investors'],
                'customer_segments' => ['enterprise', 'commercial'],
                'target_revenue' => 750000.00,
                'is_active' => true,
                'priority_level' => 5 // High priority
            ]
        ];

        foreach ($territories as $territoryData) {
            $territory = Territory::create($territoryData);
            
            // Assign some users to territories
            $users = User::where('company_id', $company->id)->take(rand(2, 4))->get();
            foreach ($users as $user) {
                $territory->assignedUsers()->attach($user->id, [
                    'role' => $user->role === 'manager' ? 'manager' : 'agent',
                    'is_primary' => $user->id === $manager->id,
                    'assigned_at' => now()
                ]);
            }
            
            $this->command->info("Created territory: {$territory->name}");
        }

        $this->command->info('Territory seeding completed!');
    }
}

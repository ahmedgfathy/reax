<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $projects = [
            [
                'name' => 'Green Valley',
                'description' => 'Luxury residential compound',
                'location' => 'New Cairo',
                'developer' => 'Prime Developments',
            ],
            [
                'name' => 'Blue Bay',
                'description' => 'Beachfront properties',
                'location' => 'North Coast',
                'developer' => 'Coastal Developers',
            ],
            // Add more sample projects
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}

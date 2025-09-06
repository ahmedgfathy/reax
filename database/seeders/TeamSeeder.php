<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('role', 'admin')->first();
        $company = Company::first();

        if (!$company || !$admin) {
            return;
        }

        $teams = [
            [
                'name' => 'Sales Team',
                'code' => '1001',
                'leader_id' => $admin->id,
                'company_id' => $company->id,
                'can_share_externally' => true,
                'public_listing_allowed' => true,
            ],
            [
                'name' => 'Marketing Team',
                'code' => '1002',
                'leader_id' => $admin->id,
                'company_id' => $company->id,
                'can_share_externally' => false,
                'public_listing_allowed' => true,
            ]
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
    }
}

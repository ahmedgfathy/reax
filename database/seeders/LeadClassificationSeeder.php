<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeadClassification;
use App\Models\Company;

class LeadClassificationSeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();
        if (!$company) {
            $this->command->error('No company found. Please run CompanySeeder first.');
            return;
        }

        $classifications = [
            [
                'company_id' => $company->id,
                'code' => 'A',
                'name' => 'Hot Lead',
                'description' => 'High potential leads with immediate buying intent',
                'priority' => 1
            ],
            [
                'company_id' => $company->id,
                'code' => 'B',
                'name' => 'Warm Lead',
                'description' => 'Moderate potential leads showing interest',
                'priority' => 2
            ],
            [
                'company_id' => $company->id,
                'code' => 'C',
                'name' => 'Cold Lead',
                'description' => 'Low potential leads requiring nurturing',
                'priority' => 3
            ]
        ];

        foreach ($classifications as $classification) {
            LeadClassification::create($classification);
        }
    }
}

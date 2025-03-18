<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $companies = [
            [
                'name' => 'Huel PLC',
                'slug' => 'huel-plc',
                'email' => 'ucummings@yost.net',
                'phone' => '567-795-0916',
                'address' => '3802 Murray Drive Penelopefurt, KS 48189-9851',
                'is_active' => true,
            ],
            // ...other companies...
        ];

        foreach ($companies as $company) {
            Company::updateOrCreate(
                ['slug' => $company['slug']],
                $company
            );
        }
    }
}

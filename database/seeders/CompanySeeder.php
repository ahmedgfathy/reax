<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run()
    {
        Company::firstOrCreate(
            ['slug' => 'default-company'],
            [
                'name' => 'Default Company',
                'email' => 'info@defaultcompany.com',
                'phone' => '1234567890',
                'is_active' => true,
            ]
        );
    }
}

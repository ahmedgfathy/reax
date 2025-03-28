<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run()
    {
        // Create default company first
        $defaultCompany = Company::updateOrCreate(
            ['email' => 'company@example.com'],
            [
                'name' => 'Default Company',
                'slug' => 'default-company',
                'phone' => '123-456-7890',
                'address' => '123 Main Street',
                'is_active' => true
            ]
        );

        $this->command->info('Default company created successfully.');
    }
}

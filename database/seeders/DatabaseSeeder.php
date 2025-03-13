<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        // Create base company
        $this->call(CompanySeeder::class);
        
        // Create users
        $this->call(UserSeeder::class);
        
        // Create properties with relationships
        \App\Models\Property::factory(50)->create([
            'company_id' => 1,
            'handler_id' => fn() => \App\Models\User::inRandomOrder()->first()->id,
        ]);
        
        // Create leads with relationships
        \App\Models\Lead::factory(50)->create([
            'company_id' => 1,
            'assigned_to' => fn() => \App\Models\User::inRandomOrder()->first()->id,
            'property_interest' => fn() => \App\Models\Property::inRandomOrder()->first()->id,
        ]);
        
        // Create events
        \App\Models\Event::factory(20)->create([
            'company_id' => 1,
            'created_by' => fn() => \App\Models\User::inRandomOrder()->first()->id,
            'lead_id' => fn() => \App\Models\Lead::inRandomOrder()->first()->id,
            'property_id' => fn() => \App\Models\Property::inRandomOrder()->first()->id,
        ]);

        // Create opportunities with relationships
        \App\Models\Opportunity::factory(10)->create([
            'company_id' => 1,
            'assigned_to' => fn() => \App\Models\User::inRandomOrder()->first()->id,
            'lead_id' => fn() => \App\Models\Lead::inRandomOrder()->first()->id,
            'property_id' => fn() => \App\Models\Property::inRandomOrder()->first()->id,
        ]);

        // Create opportunities
        $this->call(OpportunitySeeder::class);

        Schema::enableForeignKeyConstraints();
    }
}

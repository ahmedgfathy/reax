<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use App\Models\Lead;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    protected $eventTypes = ['meeting', 'call', 'site_visit', 'follow_up'];
    
    public function run()
    {
        $users = User::all();
        $leads = Lead::all();
        
        for ($i = 0; $i < 50; $i++) {
            $startDate = now()->addDays(rand(-30, 30))->addHours(rand(9, 17));
            
            Event::create([
                'title' => fake()->sentence(3),
                'description' => fake()->paragraph(),
                'event_type' => $this->eventTypes[array_rand($this->eventTypes)],
                'start_date' => $startDate,
                'end_date' => $startDate->copy()->addHours(rand(1, 3)),
                'status' => fake()->randomElement(['scheduled', 'completed', 'cancelled']),
                'location' => fake()->address(),
                'user_id' => $users->random()->id,
                'lead_id' => $leads->random()->id,
                'is_completed' => fake()->boolean(30),
                'company_id' => 1,
            ]);
        }
    }
}

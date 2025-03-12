<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run()
    {
        $leads = Lead::all();
        $users = User::all();
        $eventTypes = ['meeting', 'call', 'email', 'follow_up', 'site_visit'];
        $statuses = ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'];

        for ($i = 0; $i < 100; $i++) {
            Event::create([
                'title' => fake()->sentence(),
                'description' => fake()->paragraph(),
                'event_type' => fake()->randomElement($eventTypes),
                'event_date' => fake()->dateTimeBetween('-1 month', '+1 month'),
                'status' => fake()->randomElement($statuses),
                'is_completed' => fake()->boolean(20),
                'is_cancelled' => fake()->boolean(10),
                'lead_id' => $leads->random()->id,
                'user_id' => $users->random()->id,
            ]);
        }

        $this->command->info('Created 100 events.');
    }
}

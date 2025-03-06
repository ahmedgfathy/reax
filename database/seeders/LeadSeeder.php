<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\ActivityLog;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we already have leads
        $leadCount = Lead::count();
        if ($leadCount > 0) {
            $this->command->info("Skipping leads seeding, {$leadCount} leads already exist.");
            return;
        }
        
        // Create 50 leads
        Lead::factory()
            ->count(50)
            ->create()
            ->each(function ($lead) {
                // Create some activity logs for each lead
                $actionsCount = rand(0, 5);
                if ($actionsCount > 0) {
                    $actions = ['created_lead', 'updated_lead', 'added_note', 'scheduled_event'];
                    for ($i = 0; $i < $actionsCount; $i++) {
                        ActivityLog::create([
                            'lead_id' => $lead->id,
                            'user_id' => $lead->assigned_to,
                            'action' => $actions[array_rand($actions)],
                            // Use the name field instead of first_name and last_name
                            'description' => 'Automated activity for lead: ' . $lead->name,
                            'created_at' => $lead->created_at->addDays(rand(1, 30)),
                            'updated_at' => $lead->created_at->addDays(rand(1, 30)),
                        ]);
                    }
                }
            });

        $this->command->info('Created 50 lead records with activity logs.');
    }
}

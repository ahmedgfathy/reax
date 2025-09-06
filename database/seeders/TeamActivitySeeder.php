<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeamActivity;
use App\Models\Company;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;

class TeamActivitySeeder extends Seeder
{
    public function run(): void
    {
        // Get first company for seeding
        $company = Company::first();
        if (!$company) {
            $this->command->info('No companies found. Please seed companies first.');
            return;
        }

        // Check if activities already exist
        if (TeamActivity::count() > 0) {
            $this->command->info('Team activities already exist. Skipping seeding.');
            return;
        }

        // Get teams and users
        $team = Team::first();
        $users = User::where('company_id', $company->id)->take(5)->get();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Please seed users first.');
            return;
        }

        // Cycle through users if we don't have enough
        $getUserId = function($index) use ($users) {
            return $users[$index % $users->count()]->id;
        };

        $activities = [
            [
                'company_id' => $company->id,
                'team_id' => $team?->id,
                'user_id' => $getUserId(0),
                'type' => 'meeting',
                'title' => 'Weekly Sales Review',
                'description' => 'Review weekly sales performance and pipeline updates',
                'priority' => 'high',
                'visibility' => 'team',
                'is_system_generated' => false,
                'requires_action' => false,
                'activity_data' => [
                    'duration' => '60 minutes',
                    'attendees' => 8,
                    'location' => 'Conference Room A',
                    'status' => 'completed'
                ],
            ],
            [
                'company_id' => $company->id,
                'team_id' => $team?->id,
                'user_id' => $getUserId(1),
                'type' => 'training',
                'title' => 'CRM Training Session',
                'description' => 'Training on new CRM features and best practices',
                'priority' => 'medium',
                'visibility' => 'team',
                'is_system_generated' => false,
                'requires_action' => true,
                'activity_data' => [
                    'duration' => '2 hours',
                    'trainer' => 'John Smith',
                    'location' => 'Training Room B',
                    'status' => 'in_progress'
                ],
            ],
            [
                'company_id' => $company->id,
                'team_id' => $team?->id,
                'user_id' => $getUserId(2),
                'type' => 'follow_up',
                'title' => 'Client Follow-up Campaign',
                'description' => 'Follow up with leads from last month\'s campaign',
                'priority' => 'high',
                'visibility' => 'team',
                'is_system_generated' => false,
                'requires_action' => true,
                'activity_data' => [
                    'leads_count' => 25,
                    'campaign_type' => 'email',
                    'expected_conversion' => '15%',
                    'status' => 'pending'
                ],
            ],
            [
                'company_id' => $company->id,
                'team_id' => $team?->id,
                'user_id' => $getUserId(3),
                'type' => 'analysis',
                'title' => 'Market Analysis Report',
                'description' => 'Quarterly market analysis and competitor research',
                'priority' => 'medium',
                'visibility' => 'team',
                'is_system_generated' => false,
                'requires_action' => false,
                'activity_data' => [
                    'report_pages' => 45,
                    'competitors_analyzed' => 12,
                    'market_segments' => 5,
                    'status' => 'completed'
                ],
            ],
            [
                'company_id' => $company->id,
                'team_id' => $team?->id,
                'user_id' => $getUserId(4),
                'type' => 'presentation',
                'title' => 'Monthly Performance Presentation',
                'description' => 'Present monthly team performance to management',
                'priority' => 'high',
                'visibility' => 'team',
                'is_system_generated' => false,
                'requires_action' => true,
                'activity_data' => [
                    'audience' => 'Management Team',
                    'duration' => '45 minutes',
                    'presentation_type' => 'PowerPoint',
                    'status' => 'scheduled'
                ],
            ],
        ];

        foreach ($activities as $activityData) {
            $activity = TeamActivity::create($activityData);
            $this->command->info("Created activity: {$activity->title}");
        }

        $this->command->info('Team activity seeding completed!');
    }
}

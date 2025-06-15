<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeamPerformanceMetric;
use App\Models\Company;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;

class TeamPerformanceMetricSeeder extends Seeder
{
    public function run(): void
    {
        // Get first company for seeding
        $company = Company::first();
        if (!$company) {
            $this->command->info('No companies found. Please seed companies first.');
            return;
        }

        // Check if performance metrics already exist
        if (TeamPerformanceMetric::count() > 0) {
            $this->command->info('Team performance metrics already exist. Skipping seeding.');
            return;
        }

        // Get teams and users
        $team = Team::first();
        $users = User::where('company_id', $company->id)->take(5)->get();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Please seed users first.');
            return;
        }

        // Create monthly performance metrics for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            foreach ($users as $index => $user) {
                $baseRevenue = 10000 + ($index * 5000); // Different base revenue per user
                $randomFactor = rand(80, 120) / 100; // 80% to 120% variation
                
                $dealsCount = rand(3, 12);
                $revenueGenerated = round($baseRevenue * $randomFactor, 2);
                
                TeamPerformanceMetric::create([
                    'company_id' => $company->id,
                    'team_id' => $team?->id,
                    'user_id' => $user->id,
                    'period_start' => $date->startOfMonth(),
                    'period_end' => $date->endOfMonth(),
                    'metric_type' => 'sales',
                    'target_value' => $baseRevenue,
                    'actual_value' => $revenueGenerated,
                    'achievement_percentage' => round($randomFactor * 100, 2),
                    'period_type' => 'monthly',
                    
                    // Sales Metrics (aligned with actual DB schema)
                    'leads_generated' => rand(15, 50),
                    'leads_converted' => rand(5, 20),
                    'conversion_rate' => round(rand(15, 35), 2),
                    'revenue_generated' => $revenueGenerated,
                    'deals_closed' => $dealsCount,
                    'average_deal_size' => round($revenueGenerated / max(1, $dealsCount), 2),
                    
                    // Activity Metrics (aligned with actual DB schema)
                    'calls_made' => rand(50, 150),
                    'emails_sent' => rand(100, 300),
                    'meetings_held' => rand(10, 30),
                    'properties_shown' => rand(5, 25),
                    'follow_ups_completed' => rand(10, 40),
                    
                    // Time Tracking (aligned with actual DB schema)
                    'working_hours' => rand(120, 180), // Monthly working hours
                    'productive_hours' => rand(100, 160),
                    'productivity_score' => round(rand(70, 95), 2),
                    
                    // Ranking and Gamification (aligned with actual DB schema)
                    'team_rank' => null, // Will be calculated later
                    'company_rank' => null, // Will be calculated later
                    'points_earned' => rand(100, 500),
                    'badges_earned' => json_encode([
                        'top_performer' => $randomFactor > 1.1,
                        'revenue_achiever' => $randomFactor > 1.0,
                        'activity_champion' => rand(0, 1) === 1
                    ]),
                    
                    'notes' => $i == 0 ? 'Current month - excellent performance in sales activities' : "Month {$date->format('M Y')} performance summary",
                    'status' => $i > 0 ? 'completed' : 'active',
                ]);
            }
        }

        // Create team-level metrics
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $teamRevenue = round(rand(80000, 120000), 2);
            $teamDeals = rand(15, 40);
            
            TeamPerformanceMetric::create([
                'company_id' => $company->id,
                'team_id' => $team?->id,
                'user_id' => null, // Team-level metric
                'period_start' => $date->startOfMonth(),
                'period_end' => $date->endOfMonth(),
                'metric_type' => 'revenue',
                'target_value' => 100000,
                'actual_value' => $teamRevenue,
                'achievement_percentage' => round(($teamRevenue / 100000) * 100, 2),
                'period_type' => 'monthly',
                
                // Sales Metrics (aligned with actual DB schema)
                'leads_generated' => rand(100, 250),
                'leads_converted' => rand(30, 80),
                'conversion_rate' => round(rand(20, 40), 2),
                'revenue_generated' => $teamRevenue,
                'deals_closed' => $teamDeals,
                'average_deal_size' => round($teamRevenue / max(1, $teamDeals), 2),
                
                // Activity Metrics (aligned with actual DB schema)
                'calls_made' => rand(300, 800),
                'emails_sent' => rand(600, 1500),
                'meetings_held' => rand(50, 120),
                'properties_shown' => rand(40, 100),
                'follow_ups_completed' => rand(80, 200),
                
                // Time Tracking (aligned with actual DB schema)
                'working_hours' => rand(600, 900), // Team total monthly working hours
                'productive_hours' => rand(500, 800),
                'productivity_score' => round(rand(75, 92), 2),
                
                // Ranking and Gamification (aligned with actual DB schema)
                'team_rank' => rand(1, 5),
                'company_rank' => rand(1, 3),
                'points_earned' => rand(500, 1500),
                'badges_earned' => json_encode([
                    'team_excellence' => rand(0, 1) === 1,
                    'revenue_milestone' => $teamRevenue > 100000,
                    'collaboration_champion' => rand(0, 1) === 1,
                    'monthly_leader' => rand(0, 1) === 1
                ]),
                
                'notes' => $i == 0 ? 'Current month team performance - exceeding targets' : "Team performance for {$date->format('M Y')}",
                'status' => $i > 0 ? 'completed' : 'active',
            ]);
        }

        $this->command->info('Team performance metrics seeding completed!');
    }
}

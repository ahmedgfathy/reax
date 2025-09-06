<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Goal;
use App\Models\Company;
use App\Models\Team;
use App\Models\User;
use App\Models\Territory;
use Carbon\Carbon;

class GoalSeeder extends Seeder
{
    public function run(): void
    {
        // Get first company for seeding
        $company = Company::first();
        if (!$company) {
            $this->command->info('No companies found. Please seed companies first.');
            return;
        }

        // Check if goals already exist
        if (Goal::count() > 0) {
            $this->command->info('Goals already exist. Skipping seeding.');
            return;
        }

        // Get teams and users
        $team = Team::first();
        $users = User::where('company_id', $company->id)->take(5)->get();
        $territory = Territory::first();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Please seed users first.');
            return;
        }

        $goals = [
            [
                'company_id' => $company->id,
                'user_id' => $users->first()->id,
                'created_by' => $users->first()->id,
                'team_id' => $team?->id,
                'territory_id' => $territory?->id,
                'title' => 'Monthly Sales Target',
                'description' => 'Achieve monthly sales target of $50,000',
                'type' => 'individual',
                'category' => 'sales',
                'target_value' => 50000.00,
                'current_value' => 35000.00,
                'metric_type' => 'revenue',
                'unit' => 'currency',
                'period_type' => 'monthly',
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->endOfMonth(),
                'status' => 'active',
                'priority' => 'high',
                'auto_update' => true,
            ],
            [
                'company_id' => $company->id,
                'user_id' => null,
                'created_by' => $users->first()->id,
                'team_id' => $team?->id,
                'territory_id' => null,
                'title' => 'Quarterly Team Revenue',
                'description' => 'Team quarterly revenue target of $200,000',
                'type' => 'team',
                'category' => 'revenue',
                'target_value' => 200000.00,
                'current_value' => 120000.00,
                'metric_type' => 'revenue',
                'unit' => 'currency',
                'period_type' => 'quarterly',
                'start_date' => Carbon::now()->startOfQuarter(),
                'end_date' => Carbon::now()->endOfQuarter(),
                'status' => 'active',
                'priority' => 'high',
                'auto_update' => true,
            ],
            [
                'company_id' => $company->id,
                'user_id' => $users->skip(1)->first()->id,
                'created_by' => $users->first()->id,
                'team_id' => $team?->id,
                'territory_id' => $territory?->id,
                'title' => 'Lead Conversion Rate',
                'description' => 'Improve lead conversion rate to 25%',
                'type' => 'individual',
                'category' => 'performance',
                'target_value' => 25.00,
                'current_value' => 18.50,
                'metric_type' => 'percentage',
                'unit' => 'percentage',
                'period_type' => 'monthly',
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->addMonth(),
                'status' => 'active',
                'priority' => 'medium',
                'auto_update' => false,
            ],
            [
                'company_id' => $company->id,
                'user_id' => $users->skip(2)->first()->id,
                'created_by' => $users->first()->id,
                'team_id' => $team?->id,
                'territory_id' => null,
                'title' => 'Customer Satisfaction',
                'description' => 'Maintain customer satisfaction above 90%',
                'type' => 'individual',
                'category' => 'quality',
                'target_value' => 90.00,
                'current_value' => 87.50,
                'metric_type' => 'percentage',
                'unit' => 'percentage',
                'period_type' => 'monthly',
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->endOfMonth(),
                'status' => 'active',
                'priority' => 'medium',
                'auto_update' => false,
            ],
        ];

        foreach ($goals as $goalData) {
            $goal = Goal::create($goalData);
            $this->command->info("Created goal: {$goal->title}");
        }

        $this->command->info('Goal seeding completed!');
    }
}

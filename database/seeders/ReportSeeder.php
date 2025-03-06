<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if Report model exists and if reports table exists
        if (!class_exists(Report::class) || !$this->tableExists('reports')) {
            $this->command->warn('Reports module not installed. Skipping report seeding.');
            return;
        }
        
        // Check if we already have reports
        $reportCount = Report::count();
        if ($reportCount > 0) {
            $this->command->info("Skipping report seeding, {$reportCount} reports already exist.");
            return;
        }
        
        // Get users
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->warn("No users found. Creating a default user for report creation.");
            $users = collect([User::factory()->create()]);
        }
        
        $this->command->info("Creating diverse reports with realistic data...");
        
        // Define common report types with more specific data
        $reportTemplates = [
            // Lead performance reports - Use property_interest instead of property_id
            [
                'name' => 'Monthly Lead Acquisition',
                'description' => 'Overview of new leads acquired per month with source breakdown',
                'type' => 'lead',
                'time_range' => 'last_month',
                'columns' => ['source', 'count', 'conversion_rate', 'created_at'],
                'sort_by' => 'count',
                'sort_direction' => 'desc',
                'format' => 'excel',
                'schedule' => 'monthly',
                'is_favorite' => true,
                'status' => 'published',
                'filters' => ['status' => null]
            ],
            [
                'name' => 'Lead Conversion Analysis',
                'description' => 'Tracking lead progression through sales funnel',
                'type' => 'lead',
                'time_range' => 'last_quarter',
                'columns' => ['name', 'status', 'source', 'created_at', 'updated_at', 'assigned_to'],
                'sort_by' => 'created_at',
                'sort_direction' => 'desc',
                'format' => 'pdf',
                'schedule' => 'quarterly',
                'is_favorite' => false,
                'status' => 'published',
                'filters' => ['status' => 'qualified']
            ],
            
            // Property reports
            [
                'name' => 'Property Listing Performance',
                'description' => 'Analysis of most viewed and inquired properties',
                'type' => 'property',
                'time_range' => 'last_month',
                'columns' => ['name', 'type', 'price', 'views', 'inquiries', 'created_at'],
                'sort_by' => 'views',
                'sort_direction' => 'desc',
                'format' => 'excel',
                'schedule' => 'weekly',
                'is_favorite' => true,
                'status' => 'published',
                'filters' => ['type' => 'apartment']
            ],
            [
                'name' => 'Premium Property Analysis',
                'description' => 'Performance report of high-end properties (1M+ EGP)',
                'type' => 'property',
                'time_range' => 'year_to_date',
                'columns' => ['name', 'type', 'price', 'location', 'status', 'created_at'],
                'sort_by' => 'price',
                'sort_direction' => 'desc',
                'format' => 'pdf',
                'schedule' => 'monthly',
                'is_favorite' => false,
                'status' => 'draft',
                'filters' => ['min_price' => 1000000]
            ],
            
            // Activity reports
            [
                'name' => 'User Activity Log',
                'description' => 'Track user actions and engagement',
                'type' => 'activity',
                'time_range' => 'last_week',
                'columns' => ['user', 'action', 'lead', 'description', 'created_at'],
                'sort_by' => 'created_at',
                'sort_direction' => 'desc',
                'format' => 'csv',
                'schedule' => 'weekly',
                'is_favorite' => false,
                'status' => 'published',
                'filters' => ['action' => 'updated_lead']
            ],
            
            // Sales reports
            [
                'name' => 'Monthly Sales Report',
                'description' => 'Overview of all closed deals in the last month',
                'type' => 'sales',
                'time_range' => 'last_month',
                'columns' => ['property', 'price', 'agent', 'client', 'commission', 'date'],
                'sort_by' => 'date',
                'sort_direction' => 'desc',
                'format' => 'pdf',
                'schedule' => 'monthly',
                'is_favorite' => true,
                'status' => 'published',
                'filters' => ['status' => 'won']
            ],
            
            // Performance reports
            [
                'name' => 'Agent Performance Dashboard',
                'description' => 'Comparison of agent performance metrics',
                'type' => 'performance',
                'time_range' => 'year_to_date',
                'columns' => ['agent', 'leads_assigned', 'leads_converted', 'sales_amount', 'commission_earned', 'conversion_rate'],
                'sort_by' => 'leads_converted',
                'sort_direction' => 'desc',
                'format' => 'excel',
                'schedule' => 'monthly',
                'is_favorite' => true,
                'status' => 'published',
                'filters' => []
            ]
        ];
        
        try {
            // Create template-based reports first
            foreach ($reportTemplates as $template) {
                $user = $users->random();
                
                // Create the report
                $createdAt = Carbon::now()->subDays(rand(1, 60));
                $lastRunAt = rand(0, 10) > 3 ? $createdAt->copy()->addDays(rand(1, 10)) : null;
                
                Report::create([
                    'name' => $template['name'],
                    'description' => $template['description'],
                    'type' => $template['type'],
                    'time_range' => $template['time_range'],
                    'columns' => json_encode($template['columns']),
                    'sort_by' => $template['sort_by'],
                    'sort_direction' => $template['sort_direction'],
                    'format' => $template['format'],
                    'schedule' => $template['schedule'],
                    'is_favorite' => $template['is_favorite'],
                    'status' => $template['status'],
                    'filters' => json_encode($template['filters']),
                    'created_by' => $user->id,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                    'last_run_at' => $lastRunAt,
                ]);
            }
            
            // Create additional randomized reports with error handling
            $additionalReportsCount = 24; // To make total of 30+ reports
            
            Report::factory()
                ->count($additionalReportsCount)
                ->create();

            $this->command->info('Successfully created ' . ($additionalReportsCount + count($reportTemplates)) . ' report records.');
        } catch (\Exception $e) {
            $this->command->error('Error creating reports: ' . $e->getMessage());
            // Continue with the seeding process even if there are errors
        }
    }
    
    /**
     * Check if a table exists in the database
     */
    private function tableExists($table)
    {
        try {
            \DB::connection()->getPdo()->query("SELECT 1 FROM {$table} LIMIT 1");
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

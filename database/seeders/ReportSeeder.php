<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $reportCount = DB::table('reports')->count();
        if ($reportCount > 0) {
            $this->command->info("Skipping report seeding, {$reportCount} reports already exist.");
            return;
        }
        
        $this->command->info("Creating diverse reports with realistic data...");
        
        try {
            // Get columns from the reports table
            $reportColumns = Schema::getColumnListing('reports');
            
            // Get users
            $users = User::all();
            if ($users->isEmpty()) {
                $this->command->warn("No users found. Creating a default user for report creation.");
                $users = collect([User::factory()->create()]);
            }
            
            // Sample report definitions
            $reports = [
                [
                    'name' => 'Monthly Lead Acquisition',
                    'description' => 'Overview of new leads acquired per month with source breakdown',
                    'entity_type' => 'lead', // Use entity_type instead of type if that's the column name
                    'time_range' => 'last_month',
                    'columns' => json_encode(['source', 'count', 'conversion_rate', 'created_at']),
                    'sort_by' => 'count',
                    'sort_direction' => 'desc',
                    'format' => 'excel',
                    'schedule' => 'monthly',
                    'is_favorite' => true,
                    'status' => 'published',
                    'filters' => json_encode(['status' => null]),
                    'created_by' => $users->count() > 1 ? $users[1]->id : $users[0]->id,
                    'created_at' => Carbon::now()->subDays(rand(1, 60)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 30)),
                    'last_run_at' => Carbon::now()->subDays(rand(1, 20)),
                    'slug' => 'monthly-lead-acquisition',
                ],
                [
                    'name' => 'Property Sales Performance',
                    'description' => 'Detailed analysis of property sales by type and location',
                    'entity_type' => 'property',
                    'time_range' => 'last_quarter',
                    'columns' => json_encode(['type', 'location', 'price', 'days_listed', 'status']),
                    'sort_by' => 'price',
                    'sort_direction' => 'desc',
                    'format' => 'pdf',
                    'schedule' => 'quarterly',
                    'is_favorite' => false,
                    'status' => 'published',
                    'filters' => json_encode(['status' => 'sold']),
                    'created_by' => $users[0]->id,
                    'created_at' => Carbon::now()->subDays(rand(1, 60)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 30)),
                    'last_run_at' => Carbon::now()->subDays(rand(1, 20)),
                    'slug' => 'property-sales-performance',
                ],
                [
                    'name' => 'Team Performance Summary',
                    'description' => 'Comparison of lead conversions and sales by team',
                    'entity_type' => 'lead',
                    'time_range' => 'this_year',
                    'columns' => json_encode(['team', 'leads_count', 'conversion_rate', 'total_revenue']),
                    'sort_by' => 'total_revenue',
                    'sort_direction' => 'desc',
                    'format' => 'excel',
                    'schedule' => 'monthly',
                    'is_favorite' => true,
                    'status' => 'published',
                    'filters' => json_encode(['status' => null]),
                    'created_by' => $users[0]->id,
                    'created_at' => Carbon::now()->subDays(rand(1, 60)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 30)),
                    'last_run_at' => Carbon::now()->subDays(rand(1, 20)),
                    'slug' => 'team-performance-summary',
                ]
            ];
            
            // Filter and insert each report based on available columns
            foreach ($reports as $report) {
                // Only keep fields that exist in the database
                $filteredReport = array_intersect_key($report, array_flip($reportColumns));
                
                // Handle special case: if 'type' doesn't exist but 'entity_type' does (or vice versa)
                if (in_array('entity_type', $reportColumns) && !in_array('type', $reportColumns) && isset($report['type'])) {
                    $filteredReport['entity_type'] = $report['type'];
                    unset($filteredReport['type']);
                } else if (in_array('type', $reportColumns) && !in_array('entity_type', $reportColumns) && isset($report['entity_type'])) {
                    $filteredReport['type'] = $report['entity_type'];
                    unset($filteredReport['entity_type']);
                }
                
                // Insert the report
                DB::table('reports')->insert($filteredReport);
            }
            
            $this->command->info("Successfully created " . count($reports) . " reports.");
            
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

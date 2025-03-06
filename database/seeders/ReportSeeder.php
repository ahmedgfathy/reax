<?php

namespace Database\Seeders;

use App\Models\Report;
use Illuminate\Database\Seeder;

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
        
        // Create 15 reports
        Report::factory()
            ->count(15)
            ->create();

        $this->command->info('Created 15 report records.');
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

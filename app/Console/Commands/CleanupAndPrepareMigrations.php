<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanupAndPrepareMigrations extends Command
{
    protected $signature = 'migrations:cleanup';
    protected $description = 'Clean up and prepare database migrations for fresh start';

    public function handle()
    {
        $this->info('Starting migration cleanup process...');

        // Make sure migrations_errors table exists for logging
        $this->ensureMigrationErrorsTableExists();
        
        if (!app()->environment('production')) {
            // Drop problematic tables in development mode
            $this->dropProblematicTables();
        } else {
            $this->warn('Running in production mode. Limited actions will be taken.');
        }

        // Remove duplicate migrations from the migrations table
        $this->removeDuplicateMigrations();

        // Log consolidation in migrations_errors
        DB::table('migrations_errors')->insert([
            'migration' => 'migrations_cleanup_command',
            'error' => 'Migration tables prepared for fresh start with consolidated migrations',
            'created_at' => now()
        ]);

        $this->info('Migration cleanup process completed!');
        $this->info('You can now run php artisan migrate:fresh --seed');
        
        return Command::SUCCESS;
    }

    private function ensureMigrationErrorsTableExists()
    {
        if (!Schema::hasTable('migrations_errors')) {
            Schema::create('migrations_errors', function ($table) {
                $table->id();
                $table->string('migration');
                $table->text('error');
                $table->timestamp('created_at');
            });
            $this->info('Created migrations_errors table');
        }
    }

    private function dropProblematicTables()
    {
        $problematicTables = [
            'activity_logs',
            'events',
            'property_media'
        ];
        
        foreach ($problematicTables as $table) {
            try {
                if (Schema::hasTable($table)) {
                    Schema::dropIfExists($table);
                    $this->info("Dropped table: {$table}");
                }
            } catch (\Exception $e) {
                $this->error("Could not drop table {$table}: " . $e->getMessage());
                DB::table('migrations_errors')->insert([
                    'migration' => 'migrations_cleanup_command',
                    'error' => "Failed to drop {$table}: " . $e->getMessage(),
                    'created_at' => now()
                ]);
            }
        }
    }

    private function removeDuplicateMigrations()
    {
        // Find duplicate migrations
        $duplicates = DB::table('migrations')
            ->select('migration')
            ->groupBy('migration')
            ->havingRaw('COUNT(*) > 1')
            ->get();
            
        $this->info('Found ' . count($duplicates) . ' duplicate migrations.');
            
        foreach ($duplicates as $duplicate) {
            // Get all rows for this migration, ordered by batch
            $rows = DB::table('migrations')
                ->where('migration', $duplicate->migration)
                ->orderBy('batch', 'asc')
                ->get();
                
            // Keep the one with the lowest batch number (oldest)
            $keep = $rows->first();
            
            // Delete the others
            foreach ($rows as $row) {
                if ($row->id !== $keep->id) {
                    DB::table('migrations')
                        ->where('id', $row->id)
                        ->delete();
                    $this->info("Removed duplicate migration: {$row->migration} (ID: {$row->id}, Batch: {$row->batch})");
                }
            }
        }
    }
}

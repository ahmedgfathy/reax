<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AppwriteMigrationService;
use Exception;

class MigrateFromAppwrite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:appwrite {--dry-run : Run migration in dry-run mode without saving data} {--table= : Migrate specific table only (users,properties,leads,projects,events)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from Appwrite database to local MariaDB';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $specificTable = $this->option('table');

        $this->info('Starting Appwrite to MariaDB migration...');
        
        if ($isDryRun) {
            $this->warn('Running in DRY-RUN mode - no data will be saved to database');
        }

        try {
            // Resolve the migration service only when needed
            $migrationService = app(AppwriteMigrationService::class);
            
            // Test Appwrite connection
            $this->info('Testing Appwrite connection...');
            if (!$migrationService->testConnection()) {
                $this->error('Failed to connect to Appwrite. Please check your configuration.');
                return Command::FAILURE;
            }
            $this->info('✓ Appwrite connection successful');

            // Define migration order to handle relationships
            $migrationOrder = ['users', 'projects', 'properties', 'leads', 'events'];
            
            if ($specificTable) {
                if (!in_array($specificTable, $migrationOrder)) {
                    $this->error("Invalid table: {$specificTable}. Valid options: " . implode(', ', $migrationOrder));
                    return Command::FAILURE;
                }
                $migrationOrder = [$specificTable];
            }

            $totalCounts = [];

            foreach ($migrationOrder as $table) {
                $this->info("\n--- Migrating {$table} ---");
                
                try {
                    $count = $this->migrateTable($table, $isDryRun, $migrationService);
                    $totalCounts[$table] = $count;
                    $this->info("✓ Migrated {$count} {$table} records");
                } catch (Exception $e) {
                    $this->error("✗ Failed to migrate {$table}: " . $e->getMessage());
                    if ($this->confirm('Continue with next table?', true)) {
                        continue;
                    } else {
                        return Command::FAILURE;
                    }
                }
            }

            // Summary
            $this->info("\n=== Migration Summary ===");
            $total = 0;
            foreach ($totalCounts as $table => $count) {
                $this->info("{$table}: {$count} records");
                $total += $count;
            }
            $this->info("Total: {$total} records migrated");
            
            if ($isDryRun) {
                $this->warn('This was a dry-run. No data was actually saved to the database.');
            } else {
                $this->info('✓ Migration completed successfully!');
            }

            return Command::SUCCESS;

        } catch (Exception $e) {
            $this->error('Migration failed: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    private function migrateTable(string $table, bool $isDryRun, AppwriteMigrationService $migrationService): int
    {
        $bar = $this->output->createProgressBar();
        $bar->setFormat('verbose');

        switch ($table) {
            case 'users':
                return $migrationService->migrateUsers($isDryRun, function($progress, $total) use ($bar) {
                    $bar->setMaxSteps($total);
                    $bar->setProgress($progress);
                });
                
            case 'properties':
                return $migrationService->migrateProperties($isDryRun, function($progress, $total) use ($bar) {
                    $bar->setMaxSteps($total);
                    $bar->setProgress($progress);
                });
                
            case 'leads':
                return $migrationService->migrateLeads($isDryRun, function($progress, $total) use ($bar) {
                    $bar->setMaxSteps($total);
                    $bar->setProgress($progress);
                });
                
            case 'projects':
                return $migrationService->migrateProjects($isDryRun, function($progress, $total) use ($bar) {
                    $bar->setMaxSteps($total);
                    $bar->setProgress($progress);
                });
                
            case 'events':
                return $migrationService->migrateEvents($isDryRun, function($progress, $total) use ($bar) {
                    $bar->setMaxSteps($total);
                    $bar->setProgress($progress);
                });
                
            default:
                throw new Exception("Unknown table: {$table}");
        }
    }
}

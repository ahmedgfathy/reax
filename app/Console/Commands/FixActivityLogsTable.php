<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class FixActivityLogsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:activity-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix the activity_logs table structure for polymorphic relationships';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting activity_logs table fix...');

        // Create error tracking table if needed
        if (!Schema::hasTable('migrations_errors')) {
            $this->info('Creating migrations_errors table...');
            Schema::create('migrations_errors', function (Blueprint $table) {
                $table->id();
                $table->string('migration');
                $table->text('error');
                $table->timestamp('created_at')->useCurrent();
            });
            $this->info('migrations_errors table created.');
        }

        // Get the current table structure
        $this->info('Checking activity_logs table structure...');
        $columns = Schema::getColumnListing('activity_logs');
        $this->info('Found columns: ' . implode(', ', $columns));

        // Step 1: Check if entity_type column needs to be added
        if (!in_array('entity_type', $columns)) {
            $this->info('Adding entity_type column...');
            try {
                // Add it at the end, we'll move it later if needed
                DB::statement('ALTER TABLE activity_logs ADD COLUMN entity_type VARCHAR(255) NULL');
                $this->info('Added entity_type column.');
                
                // Refresh columns list
                $columns = Schema::getColumnListing('activity_logs');
            } catch (\Exception $e) {
                $this->error('Error adding entity_type column: ' . $e->getMessage());
                $this->logError('add_entity_type', $e->getMessage());
                return Command::FAILURE;
            }
        } else {
            $this->info('entity_type column already exists.');
        }

        // Step 2: Check if we need to convert from lead_id to entity_id
        if (in_array('lead_id', $columns) && !in_array('entity_id', $columns)) {
            $this->info('Need to convert lead_id to entity_id...');

            try {
                // Add entity_id column
                $this->info('Adding entity_id column...');
                DB::statement('ALTER TABLE activity_logs ADD COLUMN entity_id BIGINT UNSIGNED NULL');
                
                // Copy data
                $this->info('Copying data from lead_id to entity_id...');
                DB::statement('UPDATE activity_logs SET entity_id = lead_id WHERE lead_id IS NOT NULL');
                
                // Set entity_type for all records with entity_id
                $this->info('Setting entity_type to "lead" for all records...');
                DB::statement('UPDATE activity_logs SET entity_type = "lead" WHERE entity_id IS NOT NULL');
                
                // Determine if we can drop lead_id
                // First check if there are any foreign keys on the column
                $this->info('Checking for foreign keys on lead_id...');
                $hasConstraint = false;
                
                try {
                    $constraints = DB::select("
                        SELECT CONSTRAINT_NAME 
                        FROM information_schema.KEY_COLUMN_USAGE 
                        WHERE TABLE_SCHEMA = DATABASE() 
                        AND TABLE_NAME = 'activity_logs' 
                        AND COLUMN_NAME = 'lead_id' 
                        AND REFERENCED_TABLE_NAME IS NOT NULL
                    ");
                    
                    if (!empty($constraints)) {
                        $this->warn('Foreign key constraints found on lead_id column:');
                        foreach ($constraints as $constraint) {
                            $this->warn('- ' . $constraint->CONSTRAINT_NAME);
                            // Try to drop this constraint
                            $this->info('Attempting to drop constraint ' . $constraint->CONSTRAINT_NAME);
                            DB::statement("ALTER TABLE activity_logs DROP FOREIGN KEY " . $constraint->CONSTRAINT_NAME);
                        }
                    } else {
                        $this->info('No foreign key constraints found on lead_id.');
                    }
                } catch (\Exception $e) {
                    $this->warn('Error checking constraints: ' . $e->getMessage());
                    $hasConstraint = true;
                }
                
                if (!$hasConstraint) {
                    // No constraints or constraints dropped, try to drop the column
                    $this->info('Dropping lead_id column...');
                    DB::statement('ALTER TABLE activity_logs DROP COLUMN lead_id');
                    $this->info('lead_id column dropped.');
                } else {
                    $this->warn('Could not drop lead_id column due to constraints.');
                }
                
                $this->info('Conversion from lead_id to entity_id completed!');
                
                // Refresh columns list
                $columns = Schema::getColumnListing('activity_logs');
            } catch (\Exception $e) {
                $this->error('Error converting lead_id to entity_id: ' . $e->getMessage());
                $this->logError('convert_lead_id_to_entity_id', $e->getMessage());
                return Command::FAILURE;
            }
        } else if (in_array('lead_id', $columns) && in_array('entity_id', $columns)) {
            $this->info('Both lead_id and entity_id columns exist, ensuring data is copied...');
            try {
                // Copy data if entity_id is null but lead_id is not
                DB::statement('UPDATE activity_logs SET entity_id = lead_id WHERE entity_id IS NULL AND lead_id IS NOT NULL');
                DB::statement('UPDATE activity_logs SET entity_type = "lead" WHERE entity_id IS NOT NULL AND entity_type IS NULL');
                $this->info('Data synchronized between columns.');
            } catch (\Exception $e) {
                $this->error('Error synchronizing data: ' . $e->getMessage());
                $this->logError('sync_entity_id_lead_id', $e->getMessage());
                // Don't return failure, continue with process
            }
        } else if (!in_array('lead_id', $columns) && !in_array('entity_id', $columns)) {
            $this->warn('Neither lead_id nor entity_id column exists. Adding entity_id column...');
            try {
                DB::statement('ALTER TABLE activity_logs ADD COLUMN entity_id BIGINT UNSIGNED NULL');
                $this->info('entity_id column added.');
            } catch (\Exception $e) {
                $this->error('Error adding entity_id column: ' . $e->getMessage());
                $this->logError('add_entity_id', $e->getMessage());
                return Command::FAILURE;
            }
        } else {
            $this->info('entity_id column already exists.');
        }

        // Ensure entity_type is set for all entity_id values
        if (in_array('entity_id', $columns) && in_array('entity_type', $columns)) {
            $this->info('Ensuring all records with entity_id have entity_type set to "lead"...');
            try {
                DB::statement('UPDATE activity_logs SET entity_type = "lead" WHERE entity_id IS NOT NULL AND entity_type IS NULL');
                $this->info('entity_type values updated.');
            } catch (\Exception $e) {
                $this->error('Error updating entity_type: ' . $e->getMessage());
                $this->logError('update_entity_type', $e->getMessage());
                // Continue despite error
            }
        }

        // Final status
        $columns = Schema::getColumnListing('activity_logs');
        $this->info('Final activity_logs table structure: ' . implode(', ', $columns));
        
        if (in_array('entity_id', $columns) && in_array('entity_type', $columns)) {
            $this->info('Success! The activity_logs table now has the correct structure for polymorphic relationships.');
            return Command::SUCCESS;
        } else {
            $this->error('There may still be issues with the activity_logs table structure.');
            return Command::FAILURE;
        }
    }

    /**
     * Log error to the migrations_errors table
     */
    private function logError($operation, $message)
    {
        try {
            DB::table('migrations_errors')->insert([
                'migration' => 'fix:activity-logs',
                'error' => "[$operation] $message",
                'created_at' => now()
            ]);
        } catch (\Exception $e) {
            $this->error('Could not log error: ' . $e->getMessage());
        }
    }
}

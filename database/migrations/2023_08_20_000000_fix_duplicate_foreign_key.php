<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Try to drop the duplicate foreign key constraint if it exists
        try {
            // Check if the foreign key exists
            $constraintExists = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'leads' 
                AND COLUMN_NAME = 'last_modified_by' 
                AND REFERENCED_TABLE_NAME = 'users'
            ");
            
            if (!empty($constraintExists)) {
                // Get the constraint name
                $constraintName = $constraintExists[0]->CONSTRAINT_NAME;
                
                // Drop the constraint
                DB::statement("ALTER TABLE leads DROP FOREIGN KEY {$constraintName}");
                
                // Re-add the constraint with the correct name
                DB::statement("
                    ALTER TABLE leads 
                    ADD CONSTRAINT leads_last_modified_by_foreign 
                    FOREIGN KEY (last_modified_by) REFERENCES users(id) 
                    ON DELETE SET NULL
                ");
            }
        } catch (\Exception $e) {
            // Log error to migrations_errors table
            if (Schema::hasTable('migrations_errors')) {
                DB::table('migrations_errors')->insert([
                    'migration' => 'fix_duplicate_foreign_key',
                    'error' => $e->getMessage(),
                    'created_at' => now()
                ]);
            }
        }
        
        // Mark all migrations as completed so they won't run again
        $this->markMigrationsAsCompleted([
            '2023_08_16_000000_add_entity_type_to_activity_logs_table',
            '2023_08_17_000000_fix_activity_logs_structure',
            '2023_08_18_000000_fix_activity_logs_table_structure',
            '2023_08_18_000001_ensure_lead_fields_exist'
        ]);
    }
    
    /**
     * Mark specific migrations as completed
     */
    private function markMigrationsAsCompleted($migrations)
    {
        foreach ($migrations as $migration) {
            // Check if migration exists in migrations table
            $exists = DB::table('migrations')
                ->where('migration', $migration)
                ->exists();
                
            // If not, add it as completed
            if (!$exists) {
                DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => DB::table('migrations')->max('batch') + 1
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to do here
    }
};

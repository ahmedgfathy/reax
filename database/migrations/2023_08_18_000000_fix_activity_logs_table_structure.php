<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Doctrine\DBAL\Schema\SchemaException;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Check the table structure
        $columns = Schema::getColumnListing('activity_logs');
        
        // Step 2: Add entity_type column (safely)
        if (!in_array('entity_type', $columns)) {
            // Determine where to add the column
            $afterColumn = null;
            
            // First priority: after entity_id if it exists
            if (in_array('entity_id', $columns)) {
                $afterColumn = 'entity_id';
            } 
            // Second priority: after lead_id if it exists
            elseif (in_array('lead_id', $columns)) {
                $afterColumn = 'lead_id';
            }
            
            Schema::table('activity_logs', function (Blueprint $table) use ($afterColumn) {
                $column = $table->string('entity_type')->nullable();
                if ($afterColumn) {
                    $column->after($afterColumn);
                }
            });
        }
        
        // Step 3: If lead_id exists but entity_id doesn't, we need to rename it
        if (in_array('lead_id', $columns) && !in_array('entity_id', $columns)) {
            try {
                // Get all records first so we don't lose data
                $logs = DB::table('activity_logs')->get();
                
                // First drop foreign keys if they exist
                $this->dropForeignKeysIfExist('activity_logs', ['lead_id']);
                
                // Add entity_id column first
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->unsignedBigInteger('entity_id')->nullable()->after('id');
                });
                
                // Copy data from lead_id to entity_id
                foreach ($logs as $log) {
                    if (!empty($log->lead_id)) {
                        DB::table('activity_logs')
                            ->where('id', $log->id)
                            ->update(['entity_id' => $log->lead_id]);
                    }
                }
                
                // Add entity_type for all records that have an entity_id
                if (Schema::hasColumn('activity_logs', 'entity_type')) {
                    DB::table('activity_logs')
                        ->whereNotNull('entity_id')
                        ->update(['entity_type' => 'lead']);
                }
                
                // Now drop the lead_id column
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->dropColumn('lead_id');
                });
            } catch (\Exception $e) {
                // Log the error but don't fail the migration
                DB::table('migrations_errors')->insert([
                    'migration' => '2023_08_18_000000_fix_activity_logs_table_structure',
                    'error' => $e->getMessage(),
                    'created_at' => now(),
                ]);
            }
        }
        
        // Step 4: Update entity_type for existing records if needed
        if (Schema::hasColumn('activity_logs', 'entity_id') && Schema::hasColumn('activity_logs', 'entity_type')) {
            DB::table('activity_logs')
                ->whereNotNull('entity_id')
                ->whereNull('entity_type')
                ->update(['entity_type' => 'lead']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration needed
    }
    
    /**
     * Drop foreign keys that match the given columns
     */
    private function dropForeignKeysIfExist($table, $columns)
    {
        try {
            // Create migrations_errors table if it doesn't exist
            if (!Schema::hasTable('migrations_errors')) {
                Schema::create('migrations_errors', function (Blueprint $table) {
                    $table->id();
                    $table->string('migration');
                    $table->text('error');
                    $table->timestamp('created_at');
                });
            }
            
            // Get the connection
            $connection = Schema::getConnection();
            
            // Get table details
            $manager = $connection->getDoctrineSchemaManager();
            
            // Skip the check if DBAL can't list foreign keys
            try {
                $foreignKeys = $manager->listTableForeignKeys($table);
            } catch (SchemaException $e) {
                // Log it and return
                DB::table('migrations_errors')->insert([
                    'migration' => '2023_08_18_000000_fix_activity_logs_table_structure',
                    'error' => 'Could not list foreign keys: ' . $e->getMessage(),
                    'created_at' => now()
                ]);
                return;
            }
            
            // Drop each foreign key that references our columns
            foreach ($foreignKeys as $foreignKey) {
                $localColumns = $foreignKey->getLocalColumns();
                
                // Check if any of our columns are in this foreign key
                $shouldDrop = false;
                foreach ($columns as $column) {
                    if (in_array($column, $localColumns)) {
                        $shouldDrop = true;
                        break;
                    }
                }
                
                if ($shouldDrop) {
                    // Safe drop with Doctrine name
                    Schema::table($table, function (Blueprint $table) use ($foreignKey) {
                        $table->dropForeign($foreignKey->getName());
                    });
                }
            }
        } catch (\Exception $e) {
            // Log the error
            if (Schema::hasTable('migrations_errors')) {
                DB::table('migrations_errors')->insert([
                    'migration' => '2023_08_18_000000_fix_activity_logs_table_structure',
                    'error' => 'Error dropping foreign keys: ' . $e->getMessage(),
                    'created_at' => now()
                ]);
            }
        }
    }
};

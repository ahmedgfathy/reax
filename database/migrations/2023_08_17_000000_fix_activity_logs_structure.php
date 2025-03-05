<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, let's check the actual columns that exist in the activity_logs table
        $columns = Schema::getColumnListing('activity_logs');
        
        // Check if we have a column for storing entity ID
        $entityIdColumn = null;
        if (in_array('entity_id', $columns)) {
            $entityIdColumn = 'entity_id';
        } elseif (in_array('lead_id', $columns)) {
            $entityIdColumn = 'lead_id';
        }
        
        // 1. Add entity_type column if it doesn't exist yet
        if (!in_array('entity_type', $columns)) {
            Schema::table('activity_logs', function (Blueprint $table) use ($entityIdColumn) {
                // If we have an entity ID column, add entity_type after it
                if ($entityIdColumn) {
                    $table->string('entity_type')->nullable()->after($entityIdColumn);
                } else {
                    // Otherwise just add it
                    $table->string('entity_type')->nullable();
                }
            });
        }
        
        // 2. Update records if the column was added successfully and we have an entity ID column
        if (Schema::hasColumn('activity_logs', 'entity_type') && $entityIdColumn) {
            DB::statement("UPDATE activity_logs SET entity_type = 'lead' WHERE {$entityIdColumn} IS NOT NULL");
        }
        
        // 3. If we don't have an entity_id column but have a lead_id column, rename it
        if (!in_array('entity_id', $columns) && in_array('lead_id', $columns)) {
            Schema::table('activity_logs', function (Blueprint $table) {
                // First make sure there are no foreign key constraints
                if (Schema::hasColumn('activity_logs', 'lead_id')) {
                    // Drop foreign key constraint if it exists
                    $foreignKeys = $this->getForeignKeys('activity_logs');
                    foreach ($foreignKeys as $foreignKey) {
                        if ($foreignKey['column'] === 'lead_id') {
                            $table->dropForeign($foreignKey['name']);
                            break;
                        }
                    }
                }
            });
            
            // Now rename the column
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->renameColumn('lead_id', 'entity_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration needed, as this is a fix
    }
    
    /**
     * Get foreign keys for a table
     */
    private function getForeignKeys($table)
    {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();
        $foreignKeys = [];
        
        try {
            $tableForeignKeys = $conn->listTableForeignKeys($table);
            
            foreach ($tableForeignKeys as $key) {
                $foreignKeys[] = [
                    'name' => $key->getName(),
                    'column' => $key->getLocalColumns()[0]
                ];
            }
        } catch (\Exception $e) {
            // Silently fail
        }
        
        return $foreignKeys;
    }
};

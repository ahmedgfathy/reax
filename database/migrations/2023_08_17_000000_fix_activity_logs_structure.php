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
        // Ensure migrations_errors table exists
        if (!Schema::hasTable('migrations_errors')) {
            Schema::create('migrations_errors', function (Blueprint $table) {
                $table->id();
                $table->string('migration');
                $table->text('error');
                $table->timestamp('created_at');
            });
        }

        // Skip if activity_logs doesn't exist
        if (!Schema::hasTable('activity_logs')) {
            return;
        }
        
        // First, let's check the columns
        $columns = Schema::getColumnListing('activity_logs');
        
        // Check for entity ID column
        $entityIdColumn = null;
        if (in_array('entity_id', $columns)) {
            $entityIdColumn = 'entity_id';
        } elseif (in_array('lead_id', $columns)) {
            $entityIdColumn = 'lead_id';
        }
        
        // Add entity_type if needed
        if (!in_array('entity_type', $columns)) {
            Schema::table('activity_logs', function (Blueprint $table) use ($entityIdColumn) {
                if ($entityIdColumn) {
                    $table->string('entity_type')->nullable()->after($entityIdColumn);
                } else {
                    $table->string('entity_type')->nullable();
                }
            });
        }
        
        // Only add lead_id if leads table exists
        if (!Schema::hasTable('leads')) {
            DB::table('migrations_errors')->insert([
                'migration' => '2023_08_17_000000_fix_activity_logs_structure',
                'error' => 'Leads table does not exist. Cannot add foreign key.',
                'created_at' => now()
            ]);
            return;
        }

        // Add lead_id if it doesn't exist
        if (!Schema::hasColumn('activity_logs', 'lead_id')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->unsignedBigInteger('lead_id')->nullable();
            });

            // Add foreign key
            try {
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->foreign('lead_id')
                          ->references('id')
                          ->on('leads')
                          ->onDelete('cascade');
                });
            } catch (\Exception $e) {
                DB::table('migrations_errors')->insert([
                    'migration' => '2023_08_17_000000_fix_activity_logs_structure',
                    'error' => 'Failed to add foreign key: ' . $e->getMessage(),
                    'created_at' => now()
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe drop of foreign key and column
        if (Schema::hasTable('activity_logs') && Schema::hasColumn('activity_logs', 'lead_id')) {
            try {
                $foreignKeys = DB::select("SHOW CREATE TABLE activity_logs");
                if (strpos($foreignKeys[0]->{'Create Table'}, 'CONSTRAINT `activity_logs_lead_id_foreign`') !== false) {
                    Schema::table('activity_logs', function (Blueprint $table) {
                        $table->dropForeign('activity_logs_lead_id_foreign');
                    });
                }
            } catch (\Exception $e) {
                // Silently continue
            }

            Schema::table('activity_logs', function (Blueprint $table) {
                $table->dropColumn('lead_id');
            });
        }
    }
};

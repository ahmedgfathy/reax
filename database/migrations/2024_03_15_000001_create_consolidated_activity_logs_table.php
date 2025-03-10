<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop any existing activity_logs table and recreate it
        Schema::dropIfExists('activity_logs');

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('entity')->default('lead');
            $table->string('entity_type')->default('lead');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('action');
            $table->text('description')->nullable();
            $table->json('details')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->string('permission_key')->nullable();
            $table->string('action_type')->nullable();
            $table->timestamps();

            // Define comprehensive indexes for performance
            $table->index(['entity_type', 'entity_id']);
            $table->index('action');
            $table->index('created_at');
            $table->index(['company_id', 'created_at']);
            $table->index(['team_id', 'created_at']);
            $table->index(['entity_type', 'entity_id', 'company_id']);
        });

        // Mark old migrations as completed
        $this->markMigrationsAsCompleted([
            '2023_08_15_000001_create_activity_logs_table',
            '2023_08_16_000000_add_entity_type_to_activity_logs_table',
            '2023_08_17_000000_fix_activity_logs_structure',
            '2023_08_18_000000_fix_activity_logs_table_structure',
            '2023_10_10_000003_create_activity_logs_table',
            '2023_10_15_000001_create_activity_logs_table',
            '2024_03_07_020000_fix_activity_logs_schema',
            '2024_03_07_030000_update_activity_logs_structure',
            '2024_03_09_000000_fix_activity_logs_entity_field',
            '2024_03_10_000000_fix_activity_logs_schema',
            '2024_03_10_000002_update_activity_logs_table'
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
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
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop foreign keys that reference leads table
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $foreignKeys = DB::select("SHOW CREATE TABLE events");
                if (isset($foreignKeys[0]) && strpos($foreignKeys[0]->{'Create Table'}, 'CONSTRAINT `events_lead_id_foreign`') !== false) {
                    $table->dropForeign('events_lead_id_foreign');
                }
            });
        }

        // Drop and recreate the leads table
        Schema::dropIfExists('leads');

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('status')->default('new');
            $table->string('lead_status')->nullable();
            $table->string('lead_class')->nullable();
            $table->boolean('agent_follow_up')->default(false);
            $table->string('source')->nullable();
            $table->string('lead_source')->nullable();
            $table->string('type_of_request')->nullable();
            $table->foreignId('property_interest')->nullable()->constrained('properties')->nullOnDelete();
            $table->decimal('budget', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('last_modified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('last_follow_up')->nullable();
            $table->timestamps();
            
            // Comprehensive indexes for performance
            $table->index(['email']);
            $table->index(['phone']);
            $table->index(['mobile']);
            $table->index(['status']);
            $table->index(['lead_status']);
            $table->index(['lead_class']);
            $table->index(['created_at']);
            $table->index(['source']);
            $table->index(['assigned_to']);
            $table->index(['company_id']);
            $table->index(['user_id']);
        });

        // Mark old migrations as completed
        $this->markMigrationsAsCompleted([
            '2023_08_15_000000_add_missing_fields_to_leads_table',
            '2023_10_10_000005_create_leads_table',
            '2023_10_12_000000_create_leads_table',
            '2024_01_01_000001_create_leads_table',
            '2024_03_07_000000_modify_leads_table',
            '2024_03_07_010000_update_leads_relationships',
            '2024_03_10_000000_create_leads_table_unified',
            '2024_03_10_000001_create_leads_table',
            '2024_03_10_000003_create_leads_table'
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('leads');
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('events');
        
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('event_date');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_cancelled')->default(false);
            $table->text('completion_notes')->nullable();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            // Optimized indexes
            $table->index(['lead_id', 'event_date']);
            $table->index(['user_id', 'event_date']);
            $table->index(['event_type', 'status']);
            $table->index(['is_completed', 'is_cancelled']);
            $table->index(['company_id']);
            $table->index(['event_date']);
        });

        // Mark old migrations as completed
        $this->markMigrationsAsCompleted([
            '2023_10_15_000000_create_events_table',
            '2024_03_10_000002_create_events_table_unified',
            '2024_03_10_000004_create_events_table'
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('events');
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

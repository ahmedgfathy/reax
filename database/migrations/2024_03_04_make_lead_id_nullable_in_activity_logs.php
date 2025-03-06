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
        // Check if table and column exist
        if (Schema::hasTable('activity_logs')) {
            // Check if the column exists before trying to modify it
            if (Schema::hasColumn('activity_logs', 'lead_id')) {
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->foreignId('lead_id')->nullable()->change();
                });
            } else {
                // Log the issue so we know what happened
                if (Schema::hasTable('migrations_errors')) {
                    DB::table('migrations_errors')->insert([
                        'migration' => '2024_03_04_make_lead_id_nullable_in_activity_logs',
                        'error' => 'Column lead_id not found in activity_logs table. Migration skipped.',
                        'created_at' => now()
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse since this is a safety migration
        // The original intent was to make an existing column nullable
    }
};

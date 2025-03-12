<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Create migrations_errors table if it doesn't exist (centralized location)
        if (!Schema::hasTable('migrations_errors')) {
            Schema::create('migrations_errors', function (Blueprint $table) {
                $table->id();
                $table->string('migration');
                $table->text('error');
                $table->timestamp('created_at');
            });
        }

        // Log that we're running a cleanup
        DB::table('migrations_errors')->insert([
            'migration' => '2024_03_10_000000_cleanup_and_fix_migrations',
            'error' => 'Running cleanup migration to fix database structure',
            'created_at' => now()
        ]);

        // Ensure leads table is properly structured
        if (!Schema::hasTable('leads')) {
            // Skip - other migrations should handle this
            DB::table('migrations_errors')->insert([
                'migration' => '2024_03_10_000000_cleanup_and_fix_migrations',
                'error' => 'Leads table is missing, skipping related fixes',
                'created_at' => now()
            ]);
        }

        // Check for duplicate migrations
        $migrationCount = DB::table('migrations')
            ->select('migration')
            ->groupBy('migration')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($migrationCount->count() > 0) {
            foreach ($migrationCount as $duplicateMigration) {
                // Log duplicate
                DB::table('migrations_errors')->insert([
                    'migration' => '2024_03_10_000000_cleanup_and_fix_migrations',
                    'error' => 'Found duplicate migration: ' . $duplicateMigration->migration,
                    'created_at' => now()
                ]);

                // Remove duplicates (keep the oldest one)
                $ids = DB::table('migrations')
                    ->where('migration', $duplicateMigration->migration)
                    ->orderBy('id', 'desc')
                    ->skip(1)
                    ->take(PHP_INT_MAX)
                    ->pluck('id');

                DB::table('migrations')->whereIn('id', $ids)->delete();
            }
        }
    }

    public function down()
    {
        // No down migration needed for cleanup
    }
};

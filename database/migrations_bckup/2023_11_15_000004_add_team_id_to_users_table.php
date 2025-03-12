<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip adding team_id if it already exists in the users table
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'team_id')) {
            // First check if teams table exists
            if (!Schema::hasTable('teams')) {
                // Create error log
                DB::table('migrations_errors')->insert([
                    'migration' => '2023_11_15_000004_add_team_id_to_users_table',
                    'error' => 'Teams table does not exist. Cannot add foreign key.',
                    'created_at' => now()
                ]);
                
                // Add team_id without foreign key
                Schema::table('users', function (Blueprint $table) {
                    $table->unsignedBigInteger('team_id')->nullable();
                });
                
                return;
            }

            // Add team_id with foreign key
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->constrained()->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'team_id')) {
            Schema::table('users', function (Blueprint $table) {
                // Drop foreign key if it exists
                try {
                    $table->dropForeign(['team_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, continue
                }
                
                $table->dropColumn('team_id');
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('leads')) {
            Schema::create('leads', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->text('notes')->nullable();
                $table->string('status')->default('new');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        // Before dropping the leads table, we need to remove foreign key constraints
        // that reference it from other tables
        
        // Handle activity_logs table if it exists and has lead_id
        if (Schema::hasTable('activity_logs') && Schema::hasColumn('activity_logs', 'lead_id')) {
            try {
                Schema::table('activity_logs', function (Blueprint $table) {
                    // Check if the foreign key exists
                    $foreignKeys = DB::select("SHOW CREATE TABLE activity_logs");
                    if (isset($foreignKeys[0]) && 
                        isset($foreignKeys[0]->{'Create Table'}) && 
                        strpos($foreignKeys[0]->{'Create Table'}, 'CONSTRAINT `activity_logs_lead_id_foreign`') !== false) {
                        $table->dropForeign('activity_logs_lead_id_foreign');
                    }
                });
            } catch (\Exception $e) {
                // Log the error but continue
                if (Schema::hasTable('migrations_errors')) {
                    DB::table('migrations_errors')->insert([
                        'migration' => '2024_01_01_000001_create_leads_table (down)',
                        'error' => 'Failed to drop foreign key: ' . $e->getMessage(),
                        'created_at' => now()
                    ]);
                }
            }
        }

        // Now we can safely drop the leads table
        Schema::dropIfExists('leads');
    }
};

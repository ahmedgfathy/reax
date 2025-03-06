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
        if (Schema::hasTable('activity_logs') && !Schema::hasColumn('activity_logs', 'entity_type')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->string('entity_type')->nullable()->after('entity_id');
            });
        }
        
        // Then update the records, but only if the column was added successfully
        if (Schema::hasColumn('activity_logs', 'entity_type')) {
            DB::statement("UPDATE activity_logs SET entity_type = 'lead' WHERE entity_id IS NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('activity_logs') && Schema::hasColumn('activity_logs', 'entity_type')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->dropColumn('entity_type');
            });
        }
    }
};

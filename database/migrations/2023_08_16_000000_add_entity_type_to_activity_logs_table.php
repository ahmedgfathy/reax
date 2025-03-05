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
        // First add the column - this is the part that was failing
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_logs', 'entity_type')) {
                $table->string('entity_type')->nullable()->after('entity_id');
            }
        });
        
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
        Schema::table('activity_logs', function (Blueprint $table) {
            if (Schema::hasColumn('activity_logs', 'entity_type')) {
                $table->dropColumn('entity_type');
            }
        });
    }
};

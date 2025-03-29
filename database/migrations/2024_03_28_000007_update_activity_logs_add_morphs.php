<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Drop old entity columns if they exist
            if (Schema::hasColumn('activity_logs', 'entity_id')) {
                $table->dropColumn('entity_id');
            }
            if (Schema::hasColumn('activity_logs', 'entity_type')) {
                $table->dropColumn('entity_type');
            }
            if (Schema::hasColumn('activity_logs', 'lead_id')) {
                $table->dropColumn('lead_id');
            }

            // Add new columns if they don't exist
            if (!Schema::hasColumn('activity_logs', 'loggable_type')) {
                $table->morphs('loggable');
            }
            if (!Schema::hasColumn('activity_logs', 'action_type')) {
                $table->string('action_type')->after('event_type');
            }
            if (!Schema::hasColumn('activity_logs', 'module_name')) {
                $table->string('module_name')->after('action_type');
            }
            if (!Schema::hasColumn('activity_logs', 'old_values')) {
                $table->json('old_values')->nullable();
            }
            if (!Schema::hasColumn('activity_logs', 'new_values')) {
                $table->json('new_values')->nullable();
            }
            if (!Schema::hasColumn('activity_logs', 'ip_address')) {
                $table->string('ip_address')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropMorphs('loggable');
            $table->dropColumn([
                'action_type',
                'module_name',
                'old_values',
                'new_values',
                'ip_address'
            ]);
            
            // Restore old columns
            $table->foreignId('lead_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
};

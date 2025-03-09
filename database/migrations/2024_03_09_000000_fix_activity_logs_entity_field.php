<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // First check if the entity column exists
            if (!Schema::hasColumn('activity_logs', 'entity')) {
                $table->string('entity')->default('lead')->after('user_id');
            } else {
                // If it exists but doesn't have a default value, modify it
                DB::statement('ALTER TABLE activity_logs MODIFY entity VARCHAR(255) NOT NULL DEFAULT "lead"');
            }

            // Update any NULL values in existing records
            DB::table('activity_logs')->whereNull('entity')->update(['entity' => 'lead']);
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            if (Schema::hasColumn('activity_logs', 'entity')) {
                DB::statement('ALTER TABLE activity_logs MODIFY entity VARCHAR(255)');
            }
        });
    }
};

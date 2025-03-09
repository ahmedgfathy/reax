<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('action');
                $table->string('entity'); // Ensure this column is created
                $table->string('entity_type'); // Ensure this column is created
                $table->integer('entity_id');
                $table->timestamp('timestamp')->useCurrent();
                $table->timestamps();
            });
        } else {
            // Update existing table structure if needed
            Schema::table('activity_logs', function (Blueprint $table) {
                // Add any missing columns that should be added to existing table
                if (!Schema::hasColumn('activity_logs', 'entity')) {
                    $table->string('entity')->after('action');
                }
                if (!Schema::hasColumn('activity_logs', 'entity_type')) {
                    $table->string('entity_type')->after('entity');
                }
                if (!Schema::hasColumn('activity_logs', 'timestamp')) {
                    $table->timestamp('timestamp')->useCurrent()->after('entity_id');
                }
                if (!Schema::hasColumn('activity_logs', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    public function down()
    {
        // Don't drop the table if it was pre-existing
        if (Schema::hasTable('activity_logs')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                // Remove only columns we added
                $table->dropColumnIfExists('entity');
                $table->dropColumnIfExists('entity_type');
                $table->dropColumnIfExists('timestamp');
                // Don't drop timestamps if they were pre-existing
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Drop details column if it exists
            if (Schema::hasColumn('activity_logs', 'details')) {
                $table->dropColumn('details');
            }
            
            // Add JSON columns if they don't exist
            if (!Schema::hasColumn('activity_logs', 'old_values')) {
                $table->json('old_values')->nullable()->after('description');
            }
            if (!Schema::hasColumn('activity_logs', 'new_values')) {
                $table->json('new_values')->nullable()->after('old_values');
            }
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            if (Schema::hasColumn('activity_logs', 'old_values')) {
                $table->dropColumn('old_values');
            }
            if (Schema::hasColumn('activity_logs', 'new_values')) {
                $table->dropColumn('new_values');
            }
            if (!Schema::hasColumn('activity_logs', 'details')) {
                $table->text('details')->nullable();
            }
        });
    }
};

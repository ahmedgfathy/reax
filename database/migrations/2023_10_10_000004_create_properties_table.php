<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Instead of creating a new table, update the existing one
        if (Schema::hasTable('properties')) {
            Schema::table('properties', function (Blueprint $table) {
                // Add columns if they don't exist
                if (!Schema::hasColumn('properties', 'name')) {
                    $table->string('name')->nullable()->after('id');
                }
                if (!Schema::hasColumn('properties', 'unit_for')) {
                    $table->string('unit_for')->nullable();
                }
                if (!Schema::hasColumn('properties', 'currency')) {
                    $table->string('currency')->default('USD');
                }
                // Add any other missing columns...
            });
        }
    }

    public function down()
    {
        // No need to drop the table, just remove added columns if needed
        if (Schema::hasTable('properties')) {
            Schema::table('properties', function (Blueprint $table) {
                // List columns to drop if needed
                // $table->dropColumn(['column1', 'column2']);
            });
        }
    }
};

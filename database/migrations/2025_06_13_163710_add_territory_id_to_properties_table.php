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
        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('territory_id')->nullable()->after('company_id')->constrained()->onDelete('set null');
            
            // Add index for better performance
            $table->index(['territory_id', 'unit_for']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['territory_id']);
            $table->dropIndex(['territory_id', 'unit_for']);
            $table->dropColumn('territory_id');
        });
    }
};

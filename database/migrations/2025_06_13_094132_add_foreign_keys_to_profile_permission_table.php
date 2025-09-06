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
        Schema::table('profile_permission', function (Blueprint $table) {
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            
            $table->unique(['profile_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_permission', function (Blueprint $table) {
            $table->dropForeign(['profile_id']);
            $table->dropForeign(['permission_id']);
            $table->dropColumn(['profile_id', 'permission_id']);
        });
    }
};

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
        // Create pivot table for role-permission relationships
        if (!Schema::hasTable('role_permission')) {
            Schema::create('role_permission', function (Blueprint $table) {
                $table->id();
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->foreignId('permission_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                
                $table->unique(['role_id', 'permission_id']);
            });
        }
        
        // Create profile-permission relationships
        if (!Schema::hasTable('profile_permission')) {
            Schema::create('profile_permission', function (Blueprint $table) {
                $table->id();
                $table->foreignId('profile_id')->constrained()->onDelete('cascade');
                $table->foreignId('permission_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                
                $table->unique(['profile_id', 'permission_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_permission');
        Schema::dropIfExists('role_permission');
    }
};

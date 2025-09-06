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
        Schema::table('users', function (Blueprint $table) {
            // Add manager hierarchy
            if (!Schema::hasColumn('users', 'manager_id')) {
                $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            }
            
            // Add profile assignment
            if (!Schema::hasColumn('users', 'profile_id')) {
                $table->foreignId('profile_id')->nullable()->constrained('profiles')->onDelete('set null');
            }
            
            // Update role enum to include team_leader
            if (Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'manager', 'team_leader', 'agent', 'employee'])->default('employee')->change();
            }
            
            // Add hierarchy level for quick filtering
            if (!Schema::hasColumn('users', 'hierarchy_level')) {
                $table->integer('hierarchy_level')->default(4); // 1=admin, 2=manager, 3=team_leader, 4=employee
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'manager_id')) {
                $table->dropForeign(['manager_id']);
            }
            if (Schema::hasColumn('users', 'profile_id')) {
                $table->dropForeign(['profile_id']);
            }
            $table->dropColumn(['manager_id', 'profile_id', 'hierarchy_level']);
            $table->enum('role', ['admin', 'manager', 'agent', 'employee'])->default('employee')->change();
        });
    }
};

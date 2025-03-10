<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Create roles table if it doesn't exist
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->boolean('is_system')->default(false);
                $table->timestamps();
            });
        }

        // Create permissions table if it doesn't exist
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->boolean('is_system')->default(false);
                $table->timestamps();
            });
        }

        // Create role_permission pivot table if it doesn't exist
        if (!Schema::hasTable('role_permission')) {
            Schema::create('role_permission', function (Blueprint $table) {
                $table->foreignId('role_id')->constrained()->cascadeOnDelete();
                $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
                $table->foreignId('granted_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('granted_at');
                $table->primary(['role_id', 'permission_id']);
            });
        }

        // Add columns to users table if they don't exist
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->foreignId('role_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'company_id')) {
                $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'team_id')) {
                $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false);
            }
            if (!Schema::hasColumn('users', 'is_company_admin')) {
                $table->boolean('is_company_admin')->default(false);
            }
        });
    }

    public function down()
    {
        // Check each foreign key before trying to drop it
        Schema::table('users', function (Blueprint $table) {
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'users' 
                AND REFERENCED_TABLE_NAME IN ('roles', 'companies', 'teams')
            ");

            foreach ($foreignKeys as $foreignKey) {
                $table->dropForeign($foreignKey->CONSTRAINT_NAME);
            }

            // Drop columns if they exist
            $columns = ['role_id', 'company_id', 'team_id', 'is_admin', 'is_company_admin'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};

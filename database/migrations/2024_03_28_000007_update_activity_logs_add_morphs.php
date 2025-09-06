<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        try {
            // Handle MySQL vs PostgreSQL foreign key checks
            $this->disableForeignKeyChecks();
            
            if (Schema::hasColumn('activity_logs', 'lead_id')) {
                Schema::table('activity_logs', function (Blueprint $table) {
                    // Drop foreign key constraints
                    $table->dropForeign(['lead_id']);
                    // Drop the column
                    $table->dropColumn('lead_id');
                });
            }
            
            Schema::table('activity_logs', function (Blueprint $table) {
                // Add morphs columns if they don't exist
                if (!Schema::hasColumn('activity_logs', 'loggable_type')) {
                    $table->nullableMorphs('loggable');
                }
                
                // Add new columns
                if (!Schema::hasColumn('activity_logs', 'action_type')) {
                    $table->string('action_type')->nullable()->after('description');
                }
                if (!Schema::hasColumn('activity_logs', 'module_name')) {
                    $table->string('module_name')->nullable()->after('action_type');
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
            
            $this->enableForeignKeyChecks();
        } catch (\Exception $e) {
            $this->enableForeignKeyChecks();
            throw $e;
        }
    }

    public function down()
    {
        try {
            $this->disableForeignKeyChecks();
            
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->dropMorphs('loggable');
                
                // Add back the original column and foreign key
                $table->unsignedBigInteger('lead_id')->nullable();
                $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
                
                // Drop new columns
                $columns = ['action_type', 'module_name', 'old_values', 'new_values', 'ip_address'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('activity_logs', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
            
            $this->enableForeignKeyChecks();
        } catch (\Exception $e) {
            $this->enableForeignKeyChecks();
            throw $e;
        }
    }
    
    private function disableForeignKeyChecks()
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } elseif ($driver === 'pgsql') {
            // PostgreSQL doesn't have a direct equivalent, but we can use transactions
            // Foreign key checks are automatically handled in PostgreSQL transactions
        }
    }
    
    private function enableForeignKeyChecks()
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } elseif ($driver === 'pgsql') {
            // PostgreSQL doesn't need explicit foreign key check enabling
        }
    }
};

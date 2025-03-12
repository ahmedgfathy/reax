<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Add company context to activity logs
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            
            // Add permission context
            $table->string('permission_key')->nullable();
            $table->string('action_type')->nullable();
            
            // Add indices for better querying
            $table->index(['company_id', 'created_at']);
            $table->index(['team_id', 'created_at']);
            $table->index(['entity_type', 'entity_id', 'company_id']);
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign(['company_id', 'team_id']);
            $table->dropColumn(['company_id', 'team_id', 'permission_key', 'action_type']);
        });
    }
};

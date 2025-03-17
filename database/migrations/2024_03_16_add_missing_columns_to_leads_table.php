<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            // Adding all missing columns
            $table->string('lead_status')->nullable()->after('status');
            $table->string('lead_source')->nullable()->after('source');
            $table->text('description')->nullable();
            $table->string('type_of_request')->nullable();
            $table->boolean('agent_follow_up')->default(false);
            $table->string('lead_class')->nullable();
            $table->string('property_interest')->nullable();
            $table->dateTime('last_follow_up')->nullable();
            $table->unsignedBigInteger('last_modified_by')->nullable();
            
            // Add foreign key for last_modified_by
            $table->foreign('last_modified_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['last_modified_by']);
            
            // Drop columns
            $table->dropColumn([
                'lead_status',
                'lead_source',
                'description',
                'type_of_request',
                'agent_follow_up',
                'lead_class',
                'property_interest',
                'last_follow_up',
                'last_modified_by'
            ]);
        });
    }
};

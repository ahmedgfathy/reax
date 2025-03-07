<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            // Drop the name column if it exists
            if (Schema::hasColumn('leads', 'name')) {
                $table->dropColumn('name');
            }
            
            // Add first_name and last_name columns
            if (!Schema::hasColumn('leads', 'first_name')) {
                $table->string('first_name');
            }
            if (!Schema::hasColumn('leads', 'last_name')) {
                $table->string('last_name')->nullable();
            }
            
            // Add other missing columns
            if (!Schema::hasColumn('leads', 'lead_status')) {
                $table->string('lead_status')->nullable();
            }
            if (!Schema::hasColumn('leads', 'lead_source')) {
                $table->string('lead_source')->nullable();
            }
            if (!Schema::hasColumn('leads', 'property_interest')) {
                $table->string('property_interest')->nullable();
            }
            if (!Schema::hasColumn('leads', 'budget')) {
                $table->decimal('budget', 15, 2)->nullable();
            }
            if (!Schema::hasColumn('leads', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('leads', 'last_follow_up')) {
                $table->timestamp('last_follow_up')->nullable();
            }
            if (!Schema::hasColumn('leads', 'agent_follow_up')) {
                $table->boolean('agent_follow_up')->default(false);
            }
            if (!Schema::hasColumn('leads', 'lead_class')) {
                $table->string('lead_class')->nullable();
            }
            if (!Schema::hasColumn('leads', 'type_of_request')) {
                $table->string('type_of_request')->nullable();
            }
            
            // Add mobile column if it doesn't exist
            if (!Schema::hasColumn('leads', 'mobile')) {
                $table->string('mobile')->nullable();
            }
            if (!Schema::hasColumn('leads', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('leads', 'source')) {
                $table->string('source')->nullable();
            }
            if (!Schema::hasColumn('leads', 'notes')) {
                $table->text('notes')->nullable();
            }

            // Add user relationship columns
            if (!Schema::hasColumn('leads', 'assigned_to')) {
                $table->foreignId('assigned_to')
                      ->nullable()
                      ->constrained('users')
                      ->nullOnDelete();
            }
            if (!Schema::hasColumn('leads', 'last_modified_by')) {
                $table->foreignId('last_modified_by')
                      ->nullable()
                      ->constrained('users')
                      ->nullOnDelete();
            }
            if (!Schema::hasColumn('leads', 'status')) {
                $table->string('status')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'lead_status',
                'lead_source',
                'property_interest',
                'budget',
                'description',
                'last_follow_up',
                'agent_follow_up',
                'lead_class',
                'type_of_request',
                'mobile', // Add mobile to dropped columns
                'phone',  // Add phone to dropped columns
                'source', // Add source to dropped columns
                'notes',  // Add notes to dropped columns
                'assigned_to',
                'last_modified_by',
                'status'
            ]);
            
            // Restore the name column
            $table->string('name')->nullable();
        });
    }
};

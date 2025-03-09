<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First drop any existing foreign keys to avoid conflicts
        Schema::table('leads', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_to');
            $table->dropConstrainedForeignId('last_modified_by');
            if (Schema::hasColumn('leads', 'property_interest')) {
                $table->dropColumn('property_interest');
            }
        });

        // Now recreate columns with proper foreign keys and indexes
        Schema::table('leads', function (Blueprint $table) {
            // User relationships
            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
                  
            $table->foreignId('last_modified_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
                  
            // Property relationship
            $table->foreignId('property_interest')
                  ->nullable()
                  ->constrained('properties')
                  ->nullOnDelete();

            // Remove existing unique key if it exists
            try {
                $table->dropUnique('leads_email_unique');
            } catch (\Exception $e) {
                // Key doesn't exist, continue
            }
            
            // Add indexes for better performance
            $table->index(['email']);
            $table->index(['phone']);
            $table->index(['mobile']);
            $table->index(['status']);
            $table->index(['lead_status']);
            $table->index(['lead_class']);
            $table->index(['created_at']);
            
            // Add unique constraint with a different name
            $table->unique(['email'], 'leads_email_unique_new');
        });

        // Ensure activity_logs table has proper structure
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->string('entity_type');
                $table->unsignedBigInteger('entity_id');
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('action');
                $table->text('description')->nullable();
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->timestamps();

                $table->index(['entity_type', 'entity_id']);
                $table->index(['created_at']);
            });
        }

        // Ensure events table has proper structure
        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('event_type');
                $table->string('title');
                $table->text('description')->nullable();
                $table->dateTime('start_date');
                $table->dateTime('end_date')->nullable();
                $table->string('status')->default('pending');
                $table->timestamps();

                $table->index(['lead_id']);
                $table->index(['user_id']);
                $table->index(['event_type']);
                $table->index(['start_date']);
                $table->index(['status']);
            });
        }
    }

    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            // Remove indexes
            $table->dropIndex(['email']);
            $table->dropIndex(['phone']);
            $table->dropIndex(['mobile']);
            $table->dropIndex(['status']);
            $table->dropIndex(['lead_status']);
            $table->dropIndex(['lead_class']);
            $table->dropIndex(['created_at']);
            
            // Remove unique constraint
            $table->dropUnique('leads_email_unique_new');

            // Drop foreign keys
            $table->dropConstrainedForeignId('assigned_to');
            $table->dropConstrainedForeignId('last_modified_by');
            $table->dropConstrainedForeignId('property_interest');
        });

        // Drop tables if they were created in this migration
        Schema::dropIfExists('events');
        Schema::dropIfExists('activity_logs');
    }
};

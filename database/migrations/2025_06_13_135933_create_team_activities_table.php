<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('team_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('related_user_id')->nullable()->constrained('users')->onDelete('set null'); // For mentions, assignments
            
            // Activity Information
            $table->string('type'); // 'task_created', 'lead_assigned', 'meeting_scheduled', 'goal_achieved', 'message_posted'
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('activity_data')->nullable(); // Additional structured data
            
            // Related Entities
            $table->string('related_type')->nullable(); // 'lead', 'opportunity', 'property', 'goal', 'task'
            $table->unsignedBigInteger('related_id')->nullable();
            $table->index(['related_type', 'related_id']);
            
            // Activity Metadata
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('visibility', ['public', 'team', 'private'])->default('team');
            $table->boolean('is_system_generated')->default(false);
            $table->boolean('requires_action')->default(false);
            
            // Engagement
            $table->json('participants')->nullable(); // User IDs of participants
            $table->json('mentions')->nullable(); // User IDs mentioned
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->boolean('is_pinned')->default(false);
            
            // Timing
            $table->timestamp('occurred_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // Status and Tracking
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->json('status_history')->nullable(); // Track status changes
            $table->text('completion_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['company_id', 'team_id', 'created_at']);
            $table->index(['user_id', 'type', 'created_at']);
            $table->index(['type', 'status', 'occurred_at']);
            $table->index(['requires_action', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_activities');
    }
};

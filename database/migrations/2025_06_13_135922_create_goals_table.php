<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('territory_id')->nullable()->constrained()->onDelete('set null');
            
            // Goal Information
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['individual', 'team', 'department', 'company'])->default('individual');
            $table->enum('category', ['sales', 'revenue', 'leads', 'activities', 'performance', 'quality'])->default('sales');
            
            // Goal Metrics
            $table->string('metric_type'); // 'revenue', 'deals', 'leads', 'calls', 'meetings'
            $table->decimal('target_value', 15, 2);
            $table->decimal('current_value', 15, 2)->default(0);
            $table->string('unit'); // 'currency', 'count', 'percentage'
            $table->decimal('achievement_percentage', 5, 2)->default(0);
            
            // Time Frame
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('period_type', ['daily', 'weekly', 'monthly', 'quarterly', 'yearly', 'custom'])->default('monthly');
            
            // Progress Tracking
            $table->enum('status', ['draft', 'active', 'paused', 'completed', 'cancelled', 'overdue'])->default('draft');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->date('last_updated_date')->nullable();
            
            // Reward and Incentive
            $table->decimal('reward_amount', 15, 2)->nullable();
            $table->string('reward_type')->nullable(); // 'bonus', 'commission', 'recognition'
            $table->text('reward_description')->nullable();
            
            // Tracking and Automation
            $table->boolean('auto_update')->default(false);
            $table->json('update_rules')->nullable(); // Rules for auto-updating progress
            $table->json('milestone_checkpoints')->nullable(); // 25%, 50%, 75%, 100%
            $table->boolean('send_notifications')->default(true);
            $table->json('notification_settings')->nullable();
            
            // Review and Feedback
            $table->text('manager_notes')->nullable();
            $table->text('employee_notes')->nullable();
            $table->json('review_history')->nullable();
            $table->date('next_review_date')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['company_id', 'status', 'start_date']);
            $table->index(['user_id', 'type', 'status']);
            $table->index(['team_id', 'category', 'status']);
            $table->index(['end_date', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('goals');
    }
};

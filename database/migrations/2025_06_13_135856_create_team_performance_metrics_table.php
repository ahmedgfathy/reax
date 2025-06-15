<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('team_performance_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Individual member metrics
            $table->date('period_start');
            $table->date('period_end');
            $table->string('metric_type'); // 'sales', 'leads', 'calls', 'meetings', 'revenue'
            $table->string('period_type')->default('monthly'); // 'daily', 'weekly', 'monthly', 'quarterly', 'yearly'
            
            // Performance Data
            $table->decimal('target_value', 15, 2)->default(0);
            $table->decimal('actual_value', 15, 2)->default(0);
            $table->decimal('achievement_percentage', 5, 2)->default(0);
            
            // Sales Metrics
            $table->integer('leads_generated')->default(0);
            $table->integer('leads_converted')->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0);
            $table->decimal('revenue_generated', 15, 2)->default(0);
            $table->integer('deals_closed')->default(0);
            $table->decimal('average_deal_size', 15, 2)->default(0);
            
            // Activity Metrics
            $table->integer('calls_made')->default(0);
            $table->integer('emails_sent')->default(0);
            $table->integer('meetings_held')->default(0);
            $table->integer('properties_shown')->default(0);
            $table->integer('follow_ups_completed')->default(0);
            
            // Time Tracking
            $table->integer('working_hours')->default(0);
            $table->integer('productive_hours')->default(0);
            $table->decimal('productivity_score', 5, 2)->default(0);
            
            // Ranking and Gamification
            $table->integer('team_rank')->nullable();
            $table->integer('company_rank')->nullable();
            $table->integer('points_earned')->default(0);
            $table->json('badges_earned')->nullable();
            
            // Notes and Comments
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'review', 'completed'])->default('active');
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['company_id', 'team_id', 'period_start'], 'tpm_company_team_period');
            $table->index(['user_id', 'metric_type', 'period_start'], 'tpm_user_metric_period');
            $table->index(['period_type', 'period_start', 'period_end'], 'tpm_period_range');
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_performance_metrics');
    }
};

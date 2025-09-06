<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('territories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Territory Information
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['geographic', 'demographic', 'industry', 'product'])->default('geographic');
            
            // Geographic Data
            $table->json('geographic_boundaries')->nullable(); // Polygon coordinates
            $table->json('postal_codes')->nullable();
            $table->json('cities')->nullable();
            $table->json('regions')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('radius_km')->nullable();
            
            // Demographic Data
            $table->json('target_demographics')->nullable(); // Age, income, etc.
            $table->json('customer_segments')->nullable();
            
            // Performance Metrics
            $table->integer('total_leads')->default(0);
            $table->integer('active_opportunities')->default(0);
            $table->decimal('total_revenue', 15, 2)->default(0);
            $table->decimal('target_revenue', 15, 2)->default(0);
            $table->integer('properties_count')->default(0);
            
            // Territory Rules
            $table->json('assignment_rules')->nullable(); // Auto-assignment rules
            $table->boolean('auto_assign_leads')->default(true);
            $table->boolean('exclusive_territory')->default(false);
            $table->integer('priority_level')->default(1); // 1-5
            
            // Status and Settings
            $table->boolean('is_active')->default(true);
            $table->date('effective_from')->nullable();
            $table->date('effective_until')->nullable();
            $table->json('working_hours')->nullable(); // Operating hours for territory
            
            $table->timestamps();
            
            // Indexes
            $table->index(['company_id', 'is_active']);
            $table->index(['team_id', 'manager_id']);
            $table->index(['type', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('territories');
    }
};

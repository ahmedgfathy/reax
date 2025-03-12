<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Users table - Core table
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->boolean('is_active')->default(true);
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Projects table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('developer')->nullable();
            $table->string('status')->nullable();
            $table->date('launch_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('featured_image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Properties table
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            
            // Basic Property Information
            $table->string('property_name');
            $table->string('compound_name')->nullable();
            $table->string('property_number')->nullable();
            $table->string('unit_no')->nullable();
            $table->enum('unit_for', ['sale', 'rent']);
            $table->enum('type', ['apartment', 'villa', 'duplex', 'penthouse', 'studio', 'office', 'retail', 'land']);
            $table->string('phase')->nullable();
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->boolean('finished')->default(false);
            
            // Areas
            $table->decimal('total_area', 10, 2);
            $table->decimal('unit_area', 10, 2)->nullable();
            $table->decimal('land_area', 10, 2)->nullable();
            $table->decimal('garden_area', 10, 2)->nullable();
            $table->decimal('space_earth', 10, 2)->nullable();
            
            // Property Features
            $table->integer('rooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->json('amenities')->nullable();
            
            // Location & Status
            $table->enum('location_type', ['inside', 'outside'])->default('inside');
            $table->enum('category', ['residential', 'commercial', 'administrative'])->default('residential');
            $table->enum('status', ['available', 'sold', 'rented', 'reserved'])->default('available');
            
            // Pricing
            $table->decimal('total_price', 15, 2);
            $table->decimal('price_per_meter', 10, 2)->nullable();
            $table->enum('currency', ['EGP', 'USD', 'EUR'])->default('EGP');
            
            // Rental Details
            $table->date('rent_from')->nullable();
            $table->date('rent_to')->nullable();
            
            // Owner/Seller Information
            $table->enum('property_offered_by', ['owner', 'agent', 'company'])->default('owner');
            $table->string('owner_name')->nullable();
            $table->string('owner_mobile')->nullable();
            $table->string('owner_tel')->nullable();
            $table->enum('contact_status', ['contacted', 'pending', 'no_answer'])->nullable();
            
            // Sales Information
            $table->foreignId('handler_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('sales_person_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sales_category')->nullable();
            $table->text('sales_notes')->nullable();
            
            // Project & Description
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->text('description')->nullable();
            $table->text('features')->nullable();
            
            // Tracking & Dates
            $table->timestamp('last_follow_up')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['unit_for', 'type', 'status']);
            $table->index(['total_price', 'currency']);
            $table->index(['property_offered_by']);
            $table->index(['handler_id', 'sales_person_id']);
            $table->index(['project_id']);
            $table->index(['compound_name', 'location_type']);
        });

        // Property Media table
        Schema::create('property_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_type');
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('property_media');
        Schema::dropIfExists('properties');
        Schema::dropIfExists('projects');
    }
};

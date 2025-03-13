<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Property Media Table
        Schema::create('property_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Properties Table
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_name');
            $table->string('compound_name')->nullable();
            $table->string('property_number')->nullable();
            $table->string('unit_no')->nullable();
            $table->string('unit_for');
            $table->string('type');
            $table->string('phase')->nullable();
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->boolean('finished')->default(true);
            $table->decimal('total_area', 10, 2);
            $table->decimal('unit_area', 10, 2)->nullable();
            $table->decimal('land_area', 10, 2)->nullable();
            $table->decimal('garden_area', 10, 2)->nullable();
            $table->decimal('space_earth', 10, 2)->nullable();
            $table->integer('rooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->json('amenities')->nullable();
            $table->string('location_type')->nullable();
            $table->string('category')->nullable();
            $table->string('status')->default('available');
            $table->decimal('total_price', 15, 2);
            $table->decimal('price_per_meter', 15, 2)->nullable();
            $table->string('currency')->default('USD');
            $table->date('rent_from')->nullable();
            $table->date('rent_to')->nullable();
            $table->string('property_offered_by')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('owner_mobile')->nullable();
            $table->string('owner_tel')->nullable();
            $table->string('contact_status')->nullable();
            $table->foreignId('handler_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('sales_person_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sales_category')->nullable();
            $table->text('sales_notes')->nullable();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->timestamp('last_follow_up')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // Leads Table
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('status')->default('new');
            $table->string('source')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->foreignId('property_interest')->nullable()->constrained('properties')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('last_modified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->string('lead_class')->nullable();
            $table->timestamp('last_follow_up')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['status', 'lead_class']);
            $table->index(['assigned_to', 'user_id']);
            $table->index(['created_at', 'updated_at']);
        });

        // Opportunities Table
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status');
            $table->decimal('value', 15, 2)->nullable();
            $table->date('close_date')->nullable();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Events Table
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('event_type');
            $table->dateTime('event_date');
            $table->string('status')->default('pending');
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_cancelled')->default(false);
            $table->foreignId('lead_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('opportunities');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('property_media');
        Schema::dropIfExists('properties');
    }
};

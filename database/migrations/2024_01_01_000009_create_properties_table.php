<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('handler_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('sales_person_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Basic Information
            $table->string('property_name');
            $table->string('compound_name')->nullable();
            $table->string('property_number')->unique()->comment('Auto-generated property reference number');
            $table->string('unit_no')->nullable();
            $table->enum('unit_for', ['sale', 'rent', 'both'])->default('sale');
            $table->string('type');
            $table->string('phase')->nullable();
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->enum('category', ['residential', 'commercial', 'administrative'])->default('residential');
            $table->boolean('finished')->default(true);
            $table->enum('location_type', ['inside', 'outside'])->default('inside');
            
            // Areas
            $table->decimal('total_area', 10, 2);
            $table->decimal('unit_area', 10, 2)->nullable();
            $table->decimal('building_area', 10, 2)->nullable();
            $table->decimal('garden_area', 10, 2)->nullable();
            
            // Specifications
            $table->integer('rooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->json('features')->nullable();
            $table->json('amenities')->nullable();
            
            // Pricing
            $table->decimal('total_price', 12, 2);
            $table->decimal('price_per_meter', 10, 2)->nullable();
            $table->string('currency', 3)->default('EGP');
            $table->date('rent_from')->nullable();
            $table->date('rent_to')->nullable();
            
            // Owner contact information
            $table->string('owner_name')->nullable();
            $table->string('owner_mobile')->nullable();
            $table->string('owner_phone')->nullable();
            $table->string('owner_email')->nullable();
            $table->string('owner_contact_status')->nullable();
            
            // Sales Information
            $table->string('sales_category')->nullable();
            $table->text('sales_notes')->nullable();
            $table->enum('status', ['available', 'sold', 'rented', 'reserved'])->default('available');
            
            // Publishing
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('last_follow_up')->nullable();
            
            // Sharing
            $table->boolean('is_shared')->default(false);
            $table->json('sharing_settings')->nullable();
            
            // Add the missing description column
            $table->text('description')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
};

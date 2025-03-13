<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Properties Table
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_name');
            $table->string('property_number')->unique()->nullable();
            $table->string('compound_name')->index()->nullable();
            $table->string('unit_no')->index()->nullable();
            $table->enum('unit_for', ['sale', 'rent'])->index();
            $table->enum('type', ['apartment', 'villa', 'duplex', 'penthouse', 'studio', 'office', 'retail', 'land'])->index();
            $table->string('phase')->nullable();
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->boolean('finished')->default(true);
            
            // Areas with precision for accurate measurements
            $table->decimal('total_area', 12, 2);
            $table->decimal('unit_area', 12, 2)->nullable();
            $table->decimal('land_area', 12, 2)->nullable();
            $table->decimal('garden_area', 12, 2)->nullable();
            $table->decimal('space_earth', 12, 2)->nullable();
            
            // Property specifications
            $table->unsignedSmallInteger('rooms')->nullable();
            $table->unsignedSmallInteger('bathrooms')->nullable();
            $table->json('amenities')->nullable();
            $table->enum('location_type', ['inside', 'outside'])->default('inside');
            $table->enum('category', ['residential', 'commercial', 'administrative'])->default('residential');
            $table->enum('status', ['available', 'sold', 'rented', 'reserved', 'under_contract'])->default('available')->index();
            
            // Pricing information
            $table->decimal('total_price', 15, 2);
            $table->decimal('price_per_meter', 15, 2)->storedAs('total_price / NULLIF(total_area, 0)');
            $table->enum('currency', ['EGP', 'USD', 'EUR'])->default('EGP');
            
            // Rental details
            $table->date('rent_from')->nullable();
            $table->date('rent_to')->nullable();
            
            // Owner/Contact information
            $table->enum('property_offered_by', ['owner', 'agent', 'company'])->default('owner');
            $table->string('owner_name')->nullable();
            $table->string('owner_mobile')->nullable();
            $table->string('owner_tel')->nullable();
            $table->enum('contact_status', ['contacted', 'pending', 'no_answer'])->nullable();
            
            // Relations and metadata
            $table->foreignId('handler_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('sales_person_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sales_category')->nullable();
            $table->text('sales_notes')->nullable();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->timestamp('last_follow_up')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_published')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();

            // Composite indexes for common queries
            $table->index(['status', 'unit_for', 'type']);
            $table->index(['total_price', 'currency', 'unit_for']);
            $table->index(['location_type', 'category', 'status']);
            $table->index(['created_at', 'is_featured', 'is_published']);
        });

        // Property Media Table
        Schema::create('property_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_type')->default('image');
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('file_size')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('alt_text')->nullable();
            $table->string('title')->nullable();
            $table->timestamps();

            $table->index(['property_id', 'is_featured']);
            $table->index(['property_id', 'sort_order']);
        });

        // Add full-text search index
        DB::statement('ALTER TABLE properties ADD FULLTEXT search_index (property_name, compound_name, description)');
    }

    public function down()
    {
        Schema::dropIfExists('property_media');
        Schema::dropIfExists('properties');
    }
};

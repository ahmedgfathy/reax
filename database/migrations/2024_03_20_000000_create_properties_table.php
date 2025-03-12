<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('property_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

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
    }

    public function down()
    {
        Schema::dropIfExists('property_media');
        Schema::dropIfExists('properties');
    }
};

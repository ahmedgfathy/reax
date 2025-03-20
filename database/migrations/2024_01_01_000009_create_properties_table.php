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
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null');
            $table->string('property_name');
            $table->string('compound_name')->nullable();
            $table->string('property_number')->unique();
            $table->string('unit_no')->nullable();
            $table->enum('unit_for', ['sale', 'rent', 'both']);
            $table->string('type');
            $table->string('phase')->nullable();
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->boolean('finished')->default(true);
            $table->json('amenities')->nullable();
            $table->json('features')->nullable();
            $table->date('rent_from')->nullable();
            $table->date('rent_to')->nullable();
            $table->foreignId('handler_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('sales_person_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('last_follow_up')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->decimal('total_area', 10, 2)->nullable();
            $table->decimal('unit_area', 10, 2)->nullable();
            $table->decimal('land_area', 10, 2)->nullable();
            $table->decimal('garden_area', 10, 2)->nullable();
            $table->decimal('space_earth', 10, 2)->nullable();
            $table->decimal('total_price', 12, 2);
            $table->decimal('price_per_meter', 10, 2)->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_shared')->default(false);
            $table->boolean('is_public')->default(false);
            $table->json('sharing_settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
};

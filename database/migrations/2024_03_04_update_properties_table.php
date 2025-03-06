<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Add new fields if they don't exist
            if (!Schema::hasColumn('properties', 'compound_name')) {
                $table->string('compound_name')->nullable();
            }
            if (!Schema::hasColumn('properties', 'property_number')) {
                $table->string('property_number')->nullable();
            }
            if (!Schema::hasColumn('properties', 'unit_for')) {
                $table->string('unit_for')->nullable(); // 'rent', 'sale'
            }
            if (!Schema::hasColumn('properties', 'phase')) {
                $table->string('phase')->nullable();
            }
            if (!Schema::hasColumn('properties', 'building')) {
                $table->string('building')->nullable();
            }
            if (!Schema::hasColumn('properties', 'floor')) {
                $table->string('floor')->nullable();
            }
            if (!Schema::hasColumn('properties', 'finished')) {
                $table->string('finished')->nullable(); // 'yes', 'no', 'semi'
            }
            if (!Schema::hasColumn('properties', 'property_props')) {
                $table->text('property_props')->nullable();
            }
            if (!Schema::hasColumn('properties', 'location_type')) {
                $table->string('location_type')->nullable(); // 'inside', 'outside'
            }
            if (!Schema::hasColumn('properties', 'price_per_meter')) {
                $table->decimal('price_per_meter', 12, 2)->nullable();
            }
            if (!Schema::hasColumn('properties', 'currency')) {
                $table->string('currency')->nullable();
            }
            if (!Schema::hasColumn('properties', 'project_id')) {
                $table->unsignedBigInteger('project_id')->nullable();
            }
            if (!Schema::hasColumn('properties', 'last_follow_up')) {
                $table->timestamp('last_follow_up')->nullable();
            }
            if (!Schema::hasColumn('properties', 'category')) {
                $table->string('category')->nullable();
            }
            if (!Schema::hasColumn('properties', 'rent_from')) {
                $table->timestamp('rent_from')->nullable();
            }
            if (!Schema::hasColumn('properties', 'rent_to')) {
                $table->timestamp('rent_to')->nullable();
            }
            if (!Schema::hasColumn('properties', 'land_area')) {
                $table->decimal('land_area', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('properties', 'space_earth')) {
                $table->decimal('space_earth', 10, 2)->nullable()->default(0);
            }
            if (!Schema::hasColumn('properties', 'garden_area')) {
                $table->decimal('garden_area', 10, 2)->nullable()->default(0);
            }
            if (!Schema::hasColumn('properties', 'unit_area')) {
                $table->decimal('unit_area', 10, 2)->nullable()->default(0);
            }
            if (!Schema::hasColumn('properties', 'property_offered_by')) {
                $table->string('property_offered_by')->nullable();
            }
            if (!Schema::hasColumn('properties', 'owner_mobile')) {
                $table->string('owner_mobile')->nullable();
            }
            if (!Schema::hasColumn('properties', 'owner_tel')) {
                $table->string('owner_tel')->nullable();
            }
            if (!Schema::hasColumn('properties', 'update_calls')) {
                $table->string('update_calls')->nullable();
            }
            if (!Schema::hasColumn('properties', 'handler_id')) {
                $table->unsignedBigInteger('handler_id')->nullable();
            }
            if (!Schema::hasColumn('properties', 'sales_person_id')) {
                $table->unsignedBigInteger('sales_person_id')->nullable();
            }
            if (!Schema::hasColumn('properties', 'sales_category')) {
                $table->string('sales_category')->nullable();
            }
            if (!Schema::hasColumn('properties', 'sales_notes')) {
                $table->text('sales_notes')->nullable();
            }
            
            // Add foreign key constraints
            if (Schema::hasColumn('properties', 'project_id')) {
                $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            }
            if (Schema::hasColumn('properties', 'handler_id')) {
                $table->foreign('handler_id')->references('id')->on('users')->onDelete('set null');
            }
            if (Schema::hasColumn('properties', 'sales_person_id')) {
                $table->foreign('sales_person_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['project_id']);
            $table->dropForeign(['handler_id']);
            $table->dropForeign(['sales_person_id']);
            
            // Drop columns
            $table->dropColumn([
                'compound_name', 'property_number', 'unit_for', 'phase', 'building',
                'floor', 'finished', 'property_props', 'location_type', 'price_per_meter',
                'currency', 'project_id', 'last_follow_up', 'category', 'rent_from',
                'rent_to', 'land_area', 'space_earth', 'garden_area', 'unit_area',
                'property_offered_by', 'owner_mobile', 'owner_tel', 'update_calls', 'handler_id',
                'sales_person_id', 'sales_category', 'sales_notes'
            ]);
        });
    }
};

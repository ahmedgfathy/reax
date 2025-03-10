<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('properties')) {
            Schema::create('properties', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 15, 2)->nullable();
                $table->string('currency')->default('USD');
                $table->string('location')->nullable();
                $table->string('type')->nullable();
                $table->string('unit_for')->default('sale'); // 'rent' or 'sale'
                $table->integer('rooms')->nullable();
                $table->integer('bedrooms')->nullable();
                $table->integer('bathrooms')->nullable();
                $table->decimal('area', 10, 2)->nullable();
                $table->decimal('unit_area', 10, 2)->nullable()->default(0);
                $table->decimal('land_area', 10, 2)->nullable();
                $table->decimal('garden_area', 10, 2)->nullable()->default(0);
                $table->decimal('space_earth', 10, 2)->nullable()->default(0);
                $table->decimal('price_per_meter', 12, 2)->nullable();
                $table->string('status')->default('available');
                $table->string('compound_name')->nullable();
                $table->string('property_number')->nullable();
                $table->string('phase')->nullable();
                $table->string('building')->nullable();
                $table->string('floor')->nullable();
                $table->string('finished')->nullable(); // 'yes', 'no', 'semi'
                $table->text('property_props')->nullable();
                $table->string('location_type')->nullable(); // 'inside', 'outside'
                $table->string('category')->nullable();
                $table->timestamp('rent_from')->nullable();
                $table->timestamp('rent_to')->nullable();
                $table->string('property_offered_by')->nullable();
                $table->string('owner_name')->nullable();
                $table->string('owner_mobile')->nullable();
                $table->string('owner_tel')->nullable();
                $table->string('update_calls')->nullable();
                $table->string('image')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->text('sales_notes')->nullable();
                $table->string('sales_category')->nullable();
                $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('handler_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('sales_person_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
                $table->timestamp('last_follow_up')->nullable();
                $table->timestamps();
                $table->softDeletes();

                // Comprehensive indexes
                $table->index(['unit_for']);
                $table->index(['type']);
                $table->index(['status']);
                $table->index(['price']);
                $table->index(['unit_area']);
                $table->index(['location']);
                $table->index(['is_featured']);
                $table->index(['company_id']);
                $table->index(['user_id']);
                $table->index(['project_id']);
            });
        } else {
            // Add any missing columns to the existing properties table
            Schema::table('properties', function (Blueprint $table) {
                // ...existing code to add columns if they don't exist...
            });
        }

        // Create property_media table if it doesn't exist
        if (!Schema::hasTable('property_media')) {
            Schema::create('property_media', function (Blueprint $table) {
                $table->id();
                $table->foreignId('property_id')->constrained()->cascadeOnDelete();
                $table->string('media_type')->default('image'); // 'image', 'video', etc
                $table->string('file_path');
                $table->string('thumbnail_path')->nullable();
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                
                // Indexes
                $table->index(['property_id', 'media_type']);
                $table->index(['is_featured']);
                $table->index(['sort_order']);
            });
        }

        // Create projects table if it doesn't exist
        if (!Schema::hasTable('projects')) {
            Schema::create('projects', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('location')->nullable();
                $table->string('developer')->nullable();
                $table->string('status')->nullable(); // planned, under construction, completed
                $table->date('launch_date')->nullable();
                $table->date('completion_date')->nullable();
                $table->string('featured_image')->nullable();
                $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
                $table->timestamps();
                
                // Indexes
                $table->index(['status']);
                $table->index(['developer']);
                $table->index(['company_id']);
            });
        }

        // Mark old migrations as completed
        $this->markMigrationsAsCompleted([
            '2023_07_00_000000_create_properties_table',
            '2023_07_01_000000_add_is_featured_to_properties_table',
            '2023_07_02_000000_add_deleted_at_to_properties_table',
            '2023_10_10_000001_create_properties_table',
            '2023_10_10_000002_update_properties_table',
            '2023_10_10_000004_create_properties_table',
            '2024_03_04_create_projects_table',
            '2024_03_04_create_property_media_table',
            '2024_03_04_update_properties_table',
            '2024_03_10_000005_create_property_media_table'
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('property_media');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('properties');
    }

    /**
     * Mark specific migrations as completed
     */
    private function markMigrationsAsCompleted($migrations)
    {
        foreach ($migrations as $migration) {
            // Check if migration exists in migrations table
            $exists = DB::table('migrations')
                ->where('migration', $migration)
                ->exists();
                
            // If not, add it as completed
            if (!$exists) {
                DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => DB::table('migrations')->max('batch') + 1
                ]);
            }
        }
    }
};

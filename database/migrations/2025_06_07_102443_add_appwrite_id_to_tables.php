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
        // Add appwrite_id to users table only if it doesn't exist
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'appwrite_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('appwrite_id')->nullable()->unique()->after('id');
            });
        }
        
        // Add appwrite_id to properties table only if it doesn't exist
        if (Schema::hasTable('properties') && !Schema::hasColumn('properties', 'appwrite_id')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->string('appwrite_id')->nullable()->unique()->after('id');
            });
        }
        
        // Add appwrite_id to leads table if it exists and doesn't have the column
        if (Schema::hasTable('leads') && !Schema::hasColumn('leads', 'appwrite_id')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->string('appwrite_id')->nullable()->unique()->after('id');
            });
        }
        
        // Add appwrite_id to projects table if it exists and doesn't have the column
        if (Schema::hasTable('projects') && !Schema::hasColumn('projects', 'appwrite_id')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('appwrite_id')->nullable()->unique()->after('id');
            });
        }
        
        // Add appwrite_id to events table if it exists and doesn't have the column
        if (Schema::hasTable('events') && !Schema::hasColumn('events', 'appwrite_id')) {
            Schema::table('events', function (Blueprint $table) {
                $table->string('appwrite_id')->nullable()->unique()->after('id');
            });
        }
        
        // Add appwrite_file_id to property_media table if it exists and doesn't have the column
        if (Schema::hasTable('property_media') && !Schema::hasColumn('property_media', 'appwrite_file_id')) {
            Schema::table('property_media', function (Blueprint $table) {
                $table->string('appwrite_file_id')->nullable()->after('file_path');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove appwrite_id from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('appwrite_id');
        });
        
        // Remove appwrite_id from properties table
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('appwrite_id');
        });
        
        // Remove appwrite_id from leads table if it exists
        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->dropColumn('appwrite_id');
            });
        }
        
        // Remove appwrite_id from projects table if it exists
        if (Schema::hasTable('projects')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('appwrite_id');
            });
        }
        
        // Remove appwrite_id from events table if it exists
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('appwrite_id');
            });
        }
        
        // Remove appwrite_file_id from property_media table if it exists
        if (Schema::hasTable('property_media')) {
            Schema::table('property_media', function (Blueprint $table) {
                $table->dropColumn('appwrite_file_id');
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Users table
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
            // ...existing code from 2024_03_20_000001_create_properties_table.php...
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('users');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('properties')) {
            Schema::create('properties', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->decimal('price', 12, 2)->nullable();
                $table->string('location')->nullable();
                $table->string('type')->nullable();
                $table->integer('bedrooms')->nullable();
                $table->integer('bathrooms')->nullable();
                $table->decimal('area', 10, 2)->nullable();
                $table->string('status')->default('available');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
};

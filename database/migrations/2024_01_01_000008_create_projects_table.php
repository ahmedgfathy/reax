<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('developer')->nullable();
            $table->string('status')->default('active');
            $table->json('amenities')->nullable();
            $table->json('features')->nullable();
            $table->decimal('total_area', 12, 2)->nullable();
            $table->integer('total_units')->nullable();
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};

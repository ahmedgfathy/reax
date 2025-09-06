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
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->string('module'); // e.g., 'properties', 'leads', 'reports'
            $table->string('rule_type'); // e.g., 'access', 'validation', 'business'
            $table->json('conditions'); // Store rule conditions as JSON
            $table->json('actions'); // Store rule actions as JSON
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0); // For rule execution order
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};

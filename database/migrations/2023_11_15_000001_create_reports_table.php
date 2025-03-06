<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        echo "Running migration for reports table...\n";
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->enum('data_source', ['leads', 'properties', 'both']);
            $table->json('filters')->nullable();
            $table->json('columns');
            $table->json('visualization')->nullable();
            $table->boolean('is_public')->default(false);
            $table->enum('access_level', ['private', 'team', 'public'])->default('private');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

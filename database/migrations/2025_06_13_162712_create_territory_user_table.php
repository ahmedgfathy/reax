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
        Schema::create('territory_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('territory_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['manager', 'agent', 'coordinator'])->default('agent');
            $table->boolean('is_primary')->default(false);
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();
            
            // Ensure unique territory-user combinations
            $table->unique(['territory_id', 'user_id']);
            
            // Add indexes for better performance
            $table->index(['territory_id', 'role']);
            $table->index(['user_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('territory_user');
    }
};

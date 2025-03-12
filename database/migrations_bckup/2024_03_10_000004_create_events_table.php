<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('events');
        
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('event_date');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_cancelled')->default(false);
            $table->text('completion_notes')->nullable();
            $table->timestamps();

            // Optimized indexes
            $table->index(['lead_id', 'event_date']);
            $table->index(['user_id', 'event_date']);
            $table->index(['event_type', 'status']);
            $table->index(['is_completed', 'is_cancelled']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('status')->default('new');
            $table->string('lead_status')->nullable();
            $table->string('lead_class')->nullable();
            $table->boolean('agent_follow_up')->default(false);
            $table->string('source')->nullable();
            $table->string('lead_source')->nullable();
            $table->string('type_of_request')->nullable();
            $table->foreignId('property_interest')
                  ->nullable()
                  ->default(null)
                  ->constrained('properties')
                  ->nullOnDelete();
            $table->decimal('budget', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();
            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->foreignId('last_modified_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('last_follow_up')->nullable();
            $table->timestamps();

            $table->index(['email']);
            $table->index(['phone']);
            $table->index(['mobile']);
            $table->index(['status']);
            $table->index(['lead_status']);
            $table->index(['lead_class']);
            $table->index(['created_at']);
            $table->index(['source']);
            $table->index(['assigned_to']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('leads');
    }
};
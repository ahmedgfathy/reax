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
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null');
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
            $table->foreignId('property_interest')->nullable()->constrained('properties')->onDelete('set null');
            $table->decimal('budget', 12, 2)->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('last_modified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('last_follow_up')->nullable();
            $table->boolean('is_shared')->default(false);
            $table->json('sharing_settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leads');
    }
};

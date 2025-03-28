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
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('status')->default('new');
            $table->string('source')->nullable(); // Add this line
            $table->string('lead_source')->nullable();
            $table->string('lead_type')->nullable();
            $table->decimal('budget', 12, 2)->nullable();
            $table->text('requirements')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('last_contact')->nullable();
            $table->timestamp('next_follow_up')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leads');
    }
};

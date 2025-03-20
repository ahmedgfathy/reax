<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status')->default('new');
            $table->decimal('value', 12, 2)->nullable();
            $table->decimal('probability', 5, 2)->nullable();
            $table->date('expected_close_date')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->string('source')->nullable();
            $table->string('stage')->default('initial');
            $table->string('type')->nullable();
            $table->string('priority')->default('medium');
            $table->timestamp('last_activity_at')->nullable();
            $table->foreignId('last_modified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('opportunities');
    }
};

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
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->enum('status', ['open', 'qualified', 'proposal', 'negotiation', 'won', 'lost'])->default('open');
            $table->enum('stage', ['initial', 'meeting', 'proposal', 'negotiation', 'closing'])->default('initial');
            $table->decimal('value', 12, 2)->nullable();
            $table->integer('probability')->default(0);
            $table->timestamp('expected_close_date')->nullable();
            $table->timestamp('actual_close_date')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('opportunities');
    }
};

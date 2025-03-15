<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // Drop the table if it exists
        Schema::dropIfExists('departments');
        
        // Create the table without the self-referencing foreign key
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();  // Just create the column first
            $table->string('manager_name')->nullable();
            $table->string('manager_phone')->nullable();
            $table->string('manager_email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Then add the self-referencing foreign key
        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('departments')
                  ->nullOnDelete();
        });

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // Drop the table
        Schema::dropIfExists('departments');
        
        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }
};

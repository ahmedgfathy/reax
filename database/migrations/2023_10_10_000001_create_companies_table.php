<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Add companies related fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')->after('password')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->after('company_id')->nullable();
        });

        // Create teams table
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('company_id');
            $table->dropColumn('team_id');
        });
        Schema::dropIfExists('teams');
        Schema::dropIfExists('companies');
    }
};

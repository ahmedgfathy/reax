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
        Schema::table('properties', function (Blueprint $table) {
            $table->integer('rooms')->after('description');
            $table->integer('bathrooms')->after('rooms');
            $table->string('currency')->after('bathrooms');
            $table->string('owner_name')->after('currency');
            $table->string('image')->after('owner_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['rooms', 'bathrooms', 'currency', 'owner_name', 'image']);
        });
    }
};

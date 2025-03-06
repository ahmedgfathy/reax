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
        if (Schema::hasTable('properties')) {
            Schema::table('properties', function (Blueprint $table) {
                if (!Schema::hasColumn('properties', 'rooms')) {
                    $table->integer('rooms')->after('description');
                }
                if (!Schema::hasColumn('properties', 'bathrooms')) {
                    $table->integer('bathrooms')->after('rooms');
                }
                if (!Schema::hasColumn('properties', 'currency')) {
                    $table->string('currency')->nullable()->after('bathrooms');
                }
                if (!Schema::hasColumn('properties', 'owner_name')) {
                    $table->string('owner_name')->nullable()->after('currency');
                }
                if (!Schema::hasColumn('properties', 'image')) {
                    $table->string('image')->nullable()->after('owner_name');
                }
                if (!Schema::hasColumn('properties', 'size')) {
                    $table->integer('size')->nullable()->after('area');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('properties')) {
            Schema::table('properties', function (Blueprint $table) {
                $columns = ['rooms', 'bathrooms', 'currency', 'owner_name', 'image', 'size'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('properties', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};

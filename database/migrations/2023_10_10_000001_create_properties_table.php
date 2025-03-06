<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Skip this migration as properties table is already created in 2023_07_00_000000_create_properties_table.php
        return;
    }

    public function down()
    {
        return;
    }
};

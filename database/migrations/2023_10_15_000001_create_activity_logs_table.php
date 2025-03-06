<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    public function up()
    {
        // Skip this migration as activity_logs table is already created in 2023_08_15_000001_create_activity_logs_table.php
        return;
    }

    public function down()
    {
        // No operation needed
        return;
    }
}

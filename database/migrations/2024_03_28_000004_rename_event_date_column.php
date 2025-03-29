<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'event_date')) {
                $table->renameColumn('event_date', 'start_date');
            }
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('start_date', 'event_date');
        });
    }
};

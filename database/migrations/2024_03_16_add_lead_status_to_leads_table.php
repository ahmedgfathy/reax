<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('lead_status')->nullable()->after('status');
            $table->string('lead_source')->nullable()->after('source');
            $table->string('type_of_request')->nullable();
        });
    }

    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('lead_status');
            $table->dropColumn('lead_source');
            $table->dropColumn('type_of_request');
        });
    }
};

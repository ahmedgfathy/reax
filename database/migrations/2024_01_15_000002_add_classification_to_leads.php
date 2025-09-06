<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'lead_class')) {
                $table->dropColumn('lead_class');
            }
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->foreignId('lead_classification_id')
                  ->nullable()
                  ->after('lead_type')
                  ->constrained()
                  ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['lead_classification_id']);
            $table->dropColumn('lead_classification_id');
            $table->string('lead_class', 50)->nullable();
        });
    }
};

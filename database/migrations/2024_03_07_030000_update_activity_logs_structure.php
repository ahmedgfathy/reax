<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_logs', 'entity_type')) {
                $table->string('entity_type')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('activity_logs', 'entity_id')) {
                $table->unsignedBigInteger('entity_id')->nullable()->after('entity_type');
            }
            if (!Schema::hasColumn('activity_logs', 'details')) {
                $table->json('details')->nullable()->after('description');
            }
        });

        // Update existing records
        DB::table('activity_logs')
            ->whereNull('entity_type')
            ->update(['entity_type' => 'lead']);
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['entity_type', 'entity_id', 'details']);
        });
    }
};

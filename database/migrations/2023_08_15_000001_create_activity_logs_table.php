<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->morphs('entity');
                $table->string('action');
                $table->text('description')->nullable();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->unsignedBigInteger('leader_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->boolean('can_share_externally')->default(false);
            $table->json('shared_companies')->nullable();
            $table->json('visibility_settings')->nullable();
            $table->boolean('public_listing_allowed')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teams');
    }
};

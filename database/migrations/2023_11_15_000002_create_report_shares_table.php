<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->enum('share_type', ['email', 'whatsapp', 'pdf', 'excel']);
            $table->string('recipient')->nullable(); // Email or phone number
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->boolean('scheduled')->default(false);
            $table->string('frequency')->nullable(); // daily, weekly, monthly, etc.
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamp('next_send_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_shares');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Only create companies table if it doesn't exist
        if (!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('status')->default('pending'); // pending, approved, rejected
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Add or update columns to existing companies table
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'slug')) {
                $table->string('slug')->unique();
            }
            if (!Schema::hasColumn('companies', 'status')) {
                $table->string('status')->default('pending');
            }
            if (!Schema::hasColumn('companies', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('companies', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }
            if (!Schema::hasColumn('companies', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Add company_settings table if it doesn't exist
        if (!Schema::hasTable('company_settings')) {
            Schema::create('company_settings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained()->cascadeOnDelete();
                $table->string('key');
                $table->text('value')->nullable();
                $table->timestamps();
                $table->unique(['company_id', 'key']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('company_settings');
        // Only drop companies table if it exists and we created it
        if (Schema::hasTable('companies')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropSoftDeletes();
                $table->dropColumn(['slug', 'status', 'approved_by', 'approved_at']);
            });
        }
    }
};

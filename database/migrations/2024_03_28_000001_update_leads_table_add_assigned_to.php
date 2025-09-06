<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            // Remove the old user_id column if it exists
            if (Schema::hasColumn('leads', 'user_id')) {
                $table->dropColumn('user_id');
            }
            
            // Add the new assigned_to column if it doesn't exist
            if (!Schema::hasColumn('leads', 'assigned_to')) {
                $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_to');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        });
    }
};

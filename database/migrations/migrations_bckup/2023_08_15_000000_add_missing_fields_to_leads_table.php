<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                // Add missing fields if they don't already exist
                if (!Schema::hasColumn('leads', 'mobile')) {
                    $table->string('mobile')->nullable()->after('phone');
                }
                if (!Schema::hasColumn('leads', 'budget')) {
                    $table->decimal('budget', 12, 2)->nullable()->after('phone');
                }
                if (!Schema::hasColumn('leads', 'description')) {
                    $table->text('description')->nullable()->after('notes');
                }
                if (!Schema::hasColumn('leads', 'last_follow_up')) {
                    $table->timestamp('last_follow_up')->nullable();
                }
                if (!Schema::hasColumn('leads', 'agent_follow_up')) {
                    $table->boolean('agent_follow_up')->default(false);
                }
                if (!Schema::hasColumn('leads', 'lead_class')) {
                    $table->string('lead_class')->nullable();
                }
                if (!Schema::hasColumn('leads', 'last_modified_by')) {
                    $table->foreignId('last_modified_by')->nullable()->constrained('users')->onDelete('set null');
                }
                if (!Schema::hasColumn('leads', 'lead_source')) {
                    $table->string('lead_source')->nullable();
                }
                if (!Schema::hasColumn('leads', 'type_of_request')) {
                    $table->string('type_of_request')->nullable();
                }
                if (!Schema::hasColumn('leads', 'lead_status')) {
                    $table->string('lead_status')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->dropColumn([
                    'mobile',
                    'budget',
                    'description',
                    'last_follow_up',
                    'agent_follow_up',
                    'lead_class',
                    'last_modified_by',
                    'lead_source',
                    'type_of_request',
                    'lead_status'
                ]);
            });
        }
    }
};

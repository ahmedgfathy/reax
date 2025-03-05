
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
        Schema::table('leads', function (Blueprint $table) {
            // Add missing fields if they don't already exist
            if (!Schema::hasColumn('leads', 'mobile')) {
                $table->string('mobile')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('leads', 'last_follow_up')) {
                $table->timestamp('last_follow_up')->nullable()->after('budget');
            }
            if (!Schema::hasColumn('leads', 'description')) {
                $table->text('description')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('leads', 'agent_follow_up')) {
                $table->boolean('agent_follow_up')->default(false)->after('last_follow_up');
            }
            if (!Schema::hasColumn('leads', 'lead_class')) {
                $table->string('lead_class')->nullable()->after('agent_follow_up');
            }
            if (!Schema::hasColumn('leads', 'last_modified_by')) {
                $table->unsignedBigInteger('last_modified_by')->nullable()->after('assigned_to');
                $table->foreign('last_modified_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('leads', 'lead_source')) {
                $table->string('lead_source')->nullable()->after('source');
            }
            if (!Schema::hasColumn('leads', 'type_of_request')) {
                $table->string('type_of_request')->nullable()->after('lead_class');
            }
            if (!Schema::hasColumn('leads', 'lead_status')) {
                $table->string('lead_status')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'mobile',
                'last_follow_up',
                'description',
                'agent_follow_up',
                'lead_class',
                'last_modified_by',
                'lead_source',
                'type_of_request',
                'lead_status'
            ]);
        });
    }
};

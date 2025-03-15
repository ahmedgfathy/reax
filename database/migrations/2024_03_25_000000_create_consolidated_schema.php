<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Create companies table first as it's referenced by others
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Base Tables (Level 1 - No Dependencies)
        $this->createCacheTable();
        $this->createUsersTable();
        
        // Base Tables (Level 2 - Depend on Level 1)
        $this->createDepartmentsTable();
        $this->createBranchesTable();
        $this->createProjectsTable();
        
        // Base Tables (Level 3 - Depend on Level 2)
        $this->createTeamsTable();
        
        // CRM Tables (Depend on Base Tables)
        $this->createPropertiesTable();
        $this->createPropertyMediaTable();
        $this->createLeadsTable();
        $this->createEventsTable();
        $this->createTasksTable();
        
        // Support Tables
        $this->createActivityLogsTable();
        $this->createReportsTable();
        $this->createReportSchedulesTable();
        $this->createSessionsTable();
        
        // Mark old migrations as completed
        $this->markMigrationsAsCompleted();
    }

    private function createCacheTable()
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
    }

    private function createUsersTable()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->string('position')->nullable();
            $table->string('address')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    private function createTeamsTable()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->foreignId('leader_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    private function createDepartmentsTable()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    private function createBranchesTable()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    private function createProjectsTable()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('developer')->nullable();
            $table->enum('status', ['planning', 'ongoing', 'completed', 'on_hold', 'cancelled'])->default('planning');
            $table->date('launch_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('amenities')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    private function createPropertiesTable()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('property_name');
            $table->string('compound_name')->nullable();
            $table->string('property_number')->nullable();
            $table->string('unit_no')->nullable();
            $table->enum('unit_for', ['sale', 'rent']);
            $table->enum('type', ['apartment', 'villa', 'duplex', 'penthouse', 'studio', 'office', 'retail', 'land']);
            $table->string('phase')->nullable();
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->boolean('finished')->default(false);
            $table->decimal('total_area', 10, 2);
            $table->decimal('unit_area', 10, 2)->nullable();
            $table->decimal('land_area', 10, 2)->nullable();
            $table->decimal('garden_area', 10, 2)->nullable();
            $table->integer('rooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->json('amenities')->nullable();
            $table->enum('location_type', ['inside', 'outside'])->default('inside');
            $table->enum('category', ['residential', 'commercial', 'administrative'])->default('residential');
            $table->enum('status', ['available', 'sold', 'rented', 'reserved'])->default('available');
            $table->decimal('total_price', 15, 2);
            $table->decimal('price_per_meter', 10, 2)->nullable();
            $table->enum('currency', ['EGP', 'USD', 'EUR'])->default('EGP');
            $table->date('rent_from')->nullable();
            $table->date('rent_to')->nullable();
            $table->enum('property_offered_by', ['owner', 'agent', 'company'])->default('owner');
            $table->string('owner_name')->nullable();
            $table->string('owner_mobile')->nullable();
            $table->string('owner_tel')->nullable();
            $table->enum('contact_status', ['contacted', 'pending', 'no_answer'])->nullable();
            $table->foreignId('handler_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('sales_person_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sales_category')->nullable();
            $table->text('sales_notes')->nullable();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->text('features')->nullable();
            $table->timestamp('last_follow_up')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['unit_for', 'type', 'status']);
            $table->index(['total_price', 'currency']);
            $table->index(['property_offered_by']);
            $table->index(['handler_id', 'sales_person_id']);
            $table->index(['project_id']);
            $table->index(['compound_name', 'location_type']);
        });
    }

    private function createPropertyMediaTable()
    {
        Schema::create('property_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_type');
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    private function createLeadsTable()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->enum('status', ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost'])->default('new');
            $table->enum('source', ['website', 'referral', 'social_media', 'direct', 'agent', 'other'])->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->foreignId('property_interest')->nullable()->constrained('properties')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->text('notes')->nullable();
            $table->enum('lead_class', ['hot', 'warm', 'cold'])->nullable();
            $table->timestamp('last_follow_up')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'lead_class']);
            $table->index(['company_id', 'team_id']);
            $table->index(['created_at', 'status']);
        });
    }

    private function createEventsTable()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('event_type', ['meeting', 'call', 'site_visit', 'follow_up', 'other']);
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->foreignId('lead_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->json('attendees')->nullable();
            $table->text('outcome')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    private function createTasksTable()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->morphs('taskable');
            $table->timestamp('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    private function createActivityLogsTable()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->string('event_type');
            $table->morphs('loggable'); // This already creates the index we need
            $table->text('description')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'created_at']);
            // Removed duplicate index since morphs() already creates it
        });
    }

    private function createReportsTable()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->enum('data_source', ['leads', 'properties', 'both']);
            $table->json('filters')->nullable();
            $table->json('columns');
            $table->json('visualization')->nullable();
            $table->boolean('is_public')->default(false);
            $table->enum('access_level', ['private', 'team', 'public'])->default('private');
            $table->timestamps();
        });
    }

    private function createReportSchedulesTable()
    {
        Schema::create('report_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'quarterly']);
            $table->json('recipients')->nullable();
            $table->time('time')->default('08:00:00');
            $table->json('days_of_week')->nullable();
            $table->integer('day_of_month')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();
        });
    }

    private function createSessionsTable()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity')->index();
        });
    }

    private function markMigrationsAsCompleted()
    {
        $batch = DB::table('migrations')->max('batch') + 1;
        
        // Get all migration files from the input
        $migrations = glob(database_path('migrations/*.php'));
        
        foreach ($migrations as $migration) {
            $name = basename($migration, '.php');
            
            if (!DB::table('migrations')->where('migration', $name)->exists()) {
                DB::table('migrations')->insert([
                    'migration' => $name,
                    'batch' => $batch
                ]);
            }
        }
    }

    public function down()
    {
        // Drop tables in reverse order to avoid foreign key constraints
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('report_schedules');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('events');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('property_media');
        Schema::dropIfExists('properties');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('users');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
    }
};

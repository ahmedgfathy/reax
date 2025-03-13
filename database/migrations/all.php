

===== FILE: 2024_03_20_000000_create_crm_schema.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->string('position')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Companies table
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

        // Departments table
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

        // Teams table
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->foreignId('leader_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Add team and company to users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
        });

        // Projects table
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

        // Properties Table (Enhanced from your existing structure)
        Schema::create('properties', function (Blueprint $table) {
            // ...existing code for properties table...
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
        });

        // Leads Table (Enhanced)
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

        // Tasks Table
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->morphs('taskable'); // For leads, properties, etc.
            $table->timestamp('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Events Table (Enhanced)
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
            $table->json('attendees')->nullable(); // Store user IDs
            $table->text('outcome')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Activity Logs
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->string('event_type');
            $table->morphs('loggable');
            $table->text('description')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'created_at']);
            $table->index(['loggable_type', 'loggable_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('events');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('properties');
        Schema::dropIfExists('projects');
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('company_id');
            $table->dropConstrainedForeignId('team_id');
            $table->dropConstrainedForeignId('department_id');
        });
        Schema::dropIfExists('teams');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('users');
    }
};


===== FILE: 2024_03_10_000000_fix_activity_logs_schema.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('activity_logs');

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('entity')->default('lead');
            $table->string('entity_type')->default('lead');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('action');
            $table->text('description')->nullable();
            $table->json('details')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
            $table->index('action');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
};


===== FILE: 2023_11_15_000003_create_report_schedules_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'quarterly']);
            $table->json('recipients')->nullable(); // List of emails or user IDs
            $table->time('time')->default('08:00:00');
            $table->json('days_of_week')->nullable(); // For weekly schedules
            $table->integer('day_of_month')->nullable(); // For monthly schedules
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_schedules');
    }
};


===== FILE: 2025_03_10_024850_create_personal_access_tokens_table.php =====

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
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};


===== FILE: 2024_01_20_000002_create_branches_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
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

    public function down()
    {
        Schema::dropIfExists('branches');
    }
};


===== FILE: 2024_03_15_000002_create_consolidated_leads_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop foreign keys that reference leads table
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $foreignKeys = DB::select("SHOW CREATE TABLE events");
                if (isset($foreignKeys[0]) && strpos($foreignKeys[0]->{'Create Table'}, 'CONSTRAINT `events_lead_id_foreign`') !== false) {
                    $table->dropForeign('events_lead_id_foreign');
                }
            });
        }

        // Drop and recreate the leads table
        Schema::dropIfExists('leads');

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('status')->default('new');
            $table->string('lead_status')->nullable();
            $table->string('lead_class')->nullable();
            $table->boolean('agent_follow_up')->default(false);
            $table->string('source')->nullable();
            $table->string('lead_source')->nullable();
            $table->string('type_of_request')->nullable();
            $table->foreignId('property_interest')->nullable()->constrained('properties')->nullOnDelete();
            $table->decimal('budget', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('last_modified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('last_follow_up')->nullable();
            $table->timestamps();
            
            // Comprehensive indexes for performance
            $table->index(['email']);
            $table->index(['phone']);
            $table->index(['mobile']);
            $table->index(['status']);
            $table->index(['lead_status']);
            $table->index(['lead_class']);
            $table->index(['created_at']);
            $table->index(['source']);
            $table->index(['assigned_to']);
            $table->index(['company_id']);
            $table->index(['user_id']);
        });

        // Mark old migrations as completed
        $this->markMigrationsAsCompleted([
            '2023_08_15_000000_add_missing_fields_to_leads_table',
            '2023_10_10_000005_create_leads_table',
            '2023_10_12_000000_create_leads_table',
            '2024_01_01_000001_create_leads_table',
            '2024_03_07_000000_modify_leads_table',
            '2024_03_07_010000_update_leads_relationships',
            '2024_03_10_000000_create_leads_table_unified',
            '2024_03_10_000001_create_leads_table',
            '2024_03_10_000003_create_leads_table'
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('leads');
    }

    /**
     * Mark specific migrations as completed
     */
    private function markMigrationsAsCompleted($migrations)
    {
        foreach ($migrations as $migration) {
            // Check if migration exists in migrations table
            $exists = DB::table('migrations')
                ->where('migration', $migration)
                ->exists();
                
            // If not, add it as completed
            if (!$exists) {
                DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => DB::table('migrations')->max('batch') + 1
                ]);
            }
        }
    }
};


===== FILE: 2023_11_15_000001_create_reports_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        echo "Running migration for reports table...\n";
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

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};


===== FILE: 2024_03_10_000002_create_properties_table.php =====



===== FILE: 2024_03_20_000000_create_core_tables.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Users table - Core table
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->boolean('is_active')->default(true);
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Projects table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('developer')->nullable();
            $table->string('status')->nullable();
            $table->date('launch_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('featured_image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Properties table
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            
            // Basic Property Information
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
            
            // Areas
            $table->decimal('total_area', 10, 2);
            $table->decimal('unit_area', 10, 2)->nullable();
            $table->decimal('land_area', 10, 2)->nullable();
            $table->decimal('garden_area', 10, 2)->nullable();
            $table->decimal('space_earth', 10, 2)->nullable();
            
            // Property Features
            $table->integer('rooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->json('amenities')->nullable();
            
            // Location & Status
            $table->enum('location_type', ['inside', 'outside'])->default('inside');
            $table->enum('category', ['residential', 'commercial', 'administrative'])->default('residential');
            $table->enum('status', ['available', 'sold', 'rented', 'reserved'])->default('available');
            
            // Pricing
            $table->decimal('total_price', 15, 2);
            $table->decimal('price_per_meter', 10, 2)->nullable();
            $table->enum('currency', ['EGP', 'USD', 'EUR'])->default('EGP');
            
            // Rental Details
            $table->date('rent_from')->nullable();
            $table->date('rent_to')->nullable();
            
            // Owner/Seller Information
            $table->enum('property_offered_by', ['owner', 'agent', 'company'])->default('owner');
            $table->string('owner_name')->nullable();
            $table->string('owner_mobile')->nullable();
            $table->string('owner_tel')->nullable();
            $table->enum('contact_status', ['contacted', 'pending', 'no_answer'])->nullable();
            
            // Sales Information
            $table->foreignId('handler_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('sales_person_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sales_category')->nullable();
            $table->text('sales_notes')->nullable();
            
            // Project & Description
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->text('description')->nullable();
            $table->text('features')->nullable();
            
            // Tracking & Dates
            $table->timestamp('last_follow_up')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['unit_for', 'type', 'status']);
            $table->index(['total_price', 'currency']);
            $table->index(['property_offered_by']);
            $table->index(['handler_id', 'sales_person_id']);
            $table->index(['project_id']);
            $table->index(['compound_name', 'location_type']);
        });

        // Property Media table
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

    public function down()
    {
        Schema::dropIfExists('property_media');
        Schema::dropIfExists('properties');
        Schema::dropIfExists('projects');
    }
};


===== FILE: 2014_10_12_000000_create_users_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('avatar')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->boolean('is_admin')->default(false);
                $table->foreignId('team_id')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};


===== FILE: 2023_08_15_000000_add_missing_fields_to_leads_table.php =====

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


===== FILE: 2024_03_10_000002_create_companies_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create companies table with full schema
        if (!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('status')->default('pending');
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Create teams table if it doesn't exist
        if (!Schema::hasTable('teams')) {
            Schema::create('teams', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->foreignId('company_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }

        // Add company and team fields to users table - with safety checks
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'company_id')) {
                $table->foreignId('company_id')->after('password')->nullable()->constrained()->cascadeOnDelete();
            }
        });

        // Create company settings table
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
        
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'company_id')) {
                    $table->dropConstrainedForeignId('company_id');
                }
            });
        }
        
        Schema::dropIfExists('teams');
        Schema::dropIfExists('companies');
    }
};


===== FILE: 2025_03_07_000001_create_user_sessions_table.php =====

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
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('last_activity');
            $table->timestamp('logged_out_at')->nullable();
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index(['user_id', 'last_activity']);
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};


===== FILE: 2024_03_20_000001_create_base_tables.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Cache table
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        // Cache locks table
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        // Failed jobs table
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // Password reset tokens table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
    }
};


===== FILE: 2024_03_20_000001_create_properties_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            
            // Basic Property Information
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
            
            // Areas
            $table->decimal('total_area', 10, 2);
            $table->decimal('unit_area', 10, 2)->nullable();
            $table->decimal('land_area', 10, 2)->nullable();
            $table->decimal('garden_area', 10, 2)->nullable();
            $table->decimal('space_earth', 10, 2)->nullable();
            
            // Property Features
            $table->integer('rooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->json('amenities')->nullable();
            
            // Location & Status
            $table->enum('location_type', ['inside', 'outside'])->default('inside');
            $table->enum('category', ['residential', 'commercial', 'administrative'])->default('residential');
            $table->enum('status', ['available', 'sold', 'rented', 'reserved'])->default('available');
            
            // Pricing
            $table->decimal('total_price', 15, 2);
            $table->decimal('price_per_meter', 10, 2)->nullable();
            $table->enum('currency', ['EGP', 'USD', 'EUR'])->default('EGP');
            
            // Rental Details
            $table->date('rent_from')->nullable();
            $table->date('rent_to')->nullable();
            
            // Owner/Seller Information
            $table->enum('property_offered_by', ['owner', 'agent', 'company'])->default('owner');
            $table->string('owner_name')->nullable();
            $table->string('owner_mobile')->nullable();
            $table->string('owner_tel')->nullable();
            $table->enum('contact_status', ['contacted', 'pending', 'no_answer'])->nullable();
            
            // Sales Information
            $table->foreignId('handler_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('sales_person_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sales_category')->nullable();
            $table->text('sales_notes')->nullable();
            
            // Project & Description
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->text('description')->nullable();
            $table->text('features')->nullable();
            
            // Tracking & Dates
            $table->timestamp('last_follow_up')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['unit_for', 'type', 'status']);
            $table->index(['total_price', 'currency']);
            $table->index(['property_offered_by']);
            $table->index(['handler_id', 'sales_person_id']);
            $table->index(['project_id']);
            $table->index(['compound_name', 'location_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
};


===== FILE: 0001_01_01_000002_create_jobs_table.php =====

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
        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->id();
                $table->string('queue');
                $table->longText('payload');
                $table->tinyInteger('attempts')->unsigned();
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
                $table->index(['queue', 'reserved_at']);
            });
        }

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};


===== FILE: 2023_11_15_000002_create_report_shares_table.php =====

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


===== FILE: 2023_08_20_000000_fix_duplicate_foreign_key.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Try to drop the duplicate foreign key constraint if it exists
        try {
            // Check if the foreign key exists
            $constraintExists = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'leads' 
                AND COLUMN_NAME = 'last_modified_by' 
                AND REFERENCED_TABLE_NAME = 'users'
            ");
            
            if (!empty($constraintExists)) {
                // Get the constraint name
                $constraintName = $constraintExists[0]->CONSTRAINT_NAME;
                
                // Drop the constraint
                DB::statement("ALTER TABLE leads DROP FOREIGN KEY {$constraintName}");
                
                // Re-add the constraint with the correct name
                DB::statement("
                    ALTER TABLE leads 
                    ADD CONSTRAINT leads_last_modified_by_foreign 
                    FOREIGN KEY (last_modified_by) REFERENCES users(id) 
                    ON DELETE SET NULL
                ");
            }
        } catch (\Exception $e) {
            // Log error to migrations_errors table
            if (Schema::hasTable('migrations_errors')) {
                DB::table('migrations_errors')->insert([
                    'migration' => 'fix_duplicate_foreign_key',
                    'error' => $e->getMessage(),
                    'created_at' => now()
                ]);
            }
        }
        
        // Mark all migrations as completed so they won't run again
        $this->markMigrationsAsCompleted([
            '2023_08_16_000000_add_entity_type_to_activity_logs_table',
            '2023_08_17_000000_fix_activity_logs_structure',
            '2023_08_18_000000_fix_activity_logs_table_structure',
            '2023_08_18_000001_ensure_lead_fields_exist'
        ]);
    }
    
    /**
     * Mark specific migrations as completed
     */
    private function markMigrationsAsCompleted($migrations)
    {
        foreach ($migrations as $migration) {
            // Check if migration exists in migrations table
            $exists = DB::table('migrations')
                ->where('migration', $migration)
                ->exists();
                
            // If not, add it as completed
            if (!$exists) {
                DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => DB::table('migrations')->max('batch') + 1
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to do here
    }
};


===== FILE: 2024_03_10_000004_create_events_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('events');
        
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('event_date');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_cancelled')->default(false);
            $table->text('completion_notes')->nullable();
            $table->timestamps();

            // Optimized indexes
            $table->index(['lead_id', 'event_date']);
            $table->index(['user_id', 'event_date']);
            $table->index(['event_type', 'status']);
            $table->index(['is_completed', 'is_cancelled']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};


===== FILE: 2024_03_07_020000_fix_activity_logs_schema.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Drop details column if it exists
            if (Schema::hasColumn('activity_logs', 'details')) {
                $table->dropColumn('details');
            }
            
            // Add JSON columns if they don't exist
            if (!Schema::hasColumn('activity_logs', 'old_values')) {
                $table->json('old_values')->nullable()->after('description');
            }
            if (!Schema::hasColumn('activity_logs', 'new_values')) {
                $table->json('new_values')->nullable()->after('old_values');
            }
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            if (Schema::hasColumn('activity_logs', 'old_values')) {
                $table->dropColumn('old_values');
            }
            if (Schema::hasColumn('activity_logs', 'new_values')) {
                $table->dropColumn('new_values');
            }
            if (!Schema::hasColumn('activity_logs', 'details')) {
                $table->text('details')->nullable();
            }
        });
    }
};


===== FILE: 0001_01_01_000001_create_cache_table.php =====

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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};


===== FILE: 2024_03_04_make_lead_id_nullable_in_activity_logs.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if table and column exist
        if (Schema::hasTable('activity_logs')) {
            // Check if the column exists before trying to modify it
            if (Schema::hasColumn('activity_logs', 'lead_id')) {
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->foreignId('lead_id')->nullable()->change();
                });
            } else {
                // Log the issue so we know what happened
                if (Schema::hasTable('migrations_errors')) {
                    DB::table('migrations_errors')->insert([
                        'migration' => '2024_03_04_make_lead_id_nullable_in_activity_logs',
                        'error' => 'Column lead_id not found in activity_logs table. Migration skipped.',
                        'created_at' => now()
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse since this is a safety migration
        // The original intent was to make an existing column nullable
    }
};


===== FILE: 2025_03_06_000001_add_profile_fields_to_users_table.php =====

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
        Schema::table('users', function (Blueprint $table) {
            // Add missing columns that are in the User model's $fillable array
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'team_id')) {
                $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'avatar']);
            
            if (Schema::hasColumn('users', 'team_id')) {
                $table->dropForeign(['team_id']);
                $table->dropColumn('team_id');
            }
        });
    }
};


===== FILE: 2023_11_15_000000_create_teams_table.php =====

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
        if (!Schema::hasTable('teams')) {
            Schema::create('teams', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};


===== FILE: 0001_01_01_000000_create_users_table.php =====

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};


===== FILE: 2023_08_18_000002_create_migrations_errors_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('migrations_errors')) {
            Schema::create('migrations_errors', function (Blueprint $table) {
                $table->id();
                $table->string('migration');
                $table->text('error');
                $table->timestamp('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('migrations_errors');
    }
};


===== FILE: 2024_03_10_000001_create_roles_and_permissions_tables.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop existing tables if they exist to avoid conflicts
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');

        // Create roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        // Create permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        // Create role_permission pivot table
        Schema::create('role_permission', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('granted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('granted_at')->useCurrent();
            $table->primary(['role_id', 'permission_id']);
        });

        // Add or update user columns
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'role_id')) {
                    $table->foreignId('role_id')->nullable()->constrained()->nullOnDelete();
                }
                if (!Schema::hasColumn('users', 'is_admin')) {
                    $table->boolean('is_admin')->default(false);
                }
                if (!Schema::hasColumn('users', 'is_company_admin')) {
                    $table->boolean('is_company_admin')->default(false);
                }
            });
        }
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        
        // Remove columns from users table
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $columns = ['role_id', 'is_admin', 'is_company_admin'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        if ($column === 'role_id') {
                            $table->dropForeign(['role_id']);
                        }
                        $table->dropColumn($column);
                    }
                }
            });
        }

        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        
        Schema::enableForeignKeyConstraints();
    }
};


===== FILE: 2023_10_15_000001_create_activity_logs_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    public function up()
    {
        // Skip this migration as activity_logs table is already created in 2023_08_15_000001_create_activity_logs_table.php
        return;
    }

    public function down()
    {
        // No operation needed
        return;
    }
}


===== FILE: 2024_03_10_000001_create_projects_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Skip if table already exists
        if (Schema::hasTable('projects')) {
            return;
        }

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('developer')->nullable();
            $table->string('status')->nullable();
            $table->date('launch_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('featured_image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};


===== FILE: 2024_03_15_000003_create_consolidated_events_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('events');
        
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('event_date');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_cancelled')->default(false);
            $table->text('completion_notes')->nullable();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            // Optimized indexes
            $table->index(['lead_id', 'event_date']);
            $table->index(['user_id', 'event_date']);
            $table->index(['event_type', 'status']);
            $table->index(['is_completed', 'is_cancelled']);
            $table->index(['company_id']);
            $table->index(['event_date']);
        });

        // Mark old migrations as completed
        $this->markMigrationsAsCompleted([
            '2023_10_15_000000_create_events_table',
            '2024_03_10_000002_create_events_table_unified',
            '2024_03_10_000004_create_events_table'
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }

    /**
     * Mark specific migrations as completed
     */
    private function markMigrationsAsCompleted($migrations)
    {
        foreach ($migrations as $migration) {
            // Check if migration exists in migrations table
            $exists = DB::table('migrations')
                ->where('migration', $migration)
                ->exists();
                
            // If not, add it as completed
            if (!$exists) {
                DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => DB::table('migrations')->max('batch') + 1
                ]);
            }
        }
    }
};


===== FILE: 2023_08_15_000000_create_migrations_errors_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('migrations_errors')) {
            Schema::create('migrations_errors', function (Blueprint $table) {
                $table->id();
                $table->string('migration');
                $table->text('error');
                $table->timestamp('created_at');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('migrations_errors');
    }
};


===== FILE: 2025_03_06_003819_add_team_id_to_users_table.php =====

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
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};


===== FILE: 2024_03_20_000000_create_projects_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('developer')->nullable();
            $table->string('status')->nullable();
            $table->date('launch_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('featured_image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};


===== FILE: 2025_03_04_074559_0001_01_01_000000_create_users_table.php =====

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
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};


===== FILE: 2024_03_10_000002_update_activity_logs_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Add company context to activity logs
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            
            // Add permission context
            $table->string('permission_key')->nullable();
            $table->string('action_type')->nullable();
            
            // Add indices for better querying
            $table->index(['company_id', 'created_at']);
            $table->index(['team_id', 'created_at']);
            $table->index(['entity_type', 'entity_id', 'company_id']);
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign(['company_id', 'team_id']);
            $table->dropColumn(['company_id', 'team_id', 'permission_key', 'action_type']);
        });
    }
};


===== FILE: 2024_03_09_000000_add_is_active_to_users_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('remember_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};


===== FILE: 2024_03_20_000001_create_base_tables (2).php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Projects table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('developer')->nullable();
            $table->string('status')->nullable();
            $table->date('launch_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('featured_image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Properties table
        Schema::create('properties', function (Blueprint $table) {
            // ...existing code from 2024_03_20_000001_create_properties_table.php...
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('users');
    }
};


===== FILE: 2024_03_20_000001_create_activity_log_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('entity');
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->string('action');
            $table->text('description')->nullable();
            $table->json('properties')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['entity', 'entity_id']);
            $table->index(['entity_type', 'action']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
};


===== FILE: 2023_08_16_000000_add_entity_type_to_activity_logs_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('activity_logs') && !Schema::hasColumn('activity_logs', 'entity_type')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->string('entity_type')->nullable()->after('entity_id');
            });
        }
        
        // Then update the records, but only if the column was added successfully
        if (Schema::hasColumn('activity_logs', 'entity_type')) {
            DB::statement("UPDATE activity_logs SET entity_type = 'lead' WHERE entity_id IS NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('activity_logs') && Schema::hasColumn('activity_logs', 'entity_type')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->dropColumn('entity_type');
            });
        }
    }
};


===== FILE: 2024_03_20_000002_create_support_tables.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Property Media table
        Schema::create('property_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_type');
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Leads table
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('status')->default('new');
            $table->string('source')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->foreignId('property_interest')->nullable()->constrained('properties')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Events table
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('event_type');
            $table->dateTime('event_date');
            $table->foreignId('lead_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('property_media');
    }
};


===== FILE: 2024_01_20_000001_create_departments_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('departments');
    }
};


===== FILE: 2024_03_15_000001_create_consolidated_activity_logs_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop any existing activity_logs table and recreate it
        Schema::dropIfExists('activity_logs');

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('entity')->default('lead');
            $table->string('entity_type')->default('lead');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('action');
            $table->text('description')->nullable();
            $table->json('details')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->string('permission_key')->nullable();
            $table->string('action_type')->nullable();
            $table->timestamps();

            // Define comprehensive indexes for performance
            $table->index(['entity_type', 'entity_id']);
            $table->index('action');
            $table->index('created_at');
            $table->index(['company_id', 'created_at']);
            $table->index(['team_id', 'created_at']);
            $table->index(['entity_type', 'entity_id', 'company_id']);
        });

        // Mark old migrations as completed
        $this->markMigrationsAsCompleted([
            '2023_08_15_000001_create_activity_logs_table',
            '2023_08_16_000000_add_entity_type_to_activity_logs_table',
            '2023_08_17_000000_fix_activity_logs_structure',
            '2023_08_18_000000_fix_activity_logs_table_structure',
            '2023_10_10_000003_create_activity_logs_table',
            '2023_10_15_000001_create_activity_logs_table',
            '2024_03_07_020000_fix_activity_logs_schema',
            '2024_03_07_030000_update_activity_logs_structure',
            '2024_03_09_000000_fix_activity_logs_entity_field',
            '2024_03_10_000000_fix_activity_logs_schema',
            '2024_03_10_000002_update_activity_logs_table'
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }

    /**
     * Mark specific migrations as completed
     */
    private function markMigrationsAsCompleted($migrations)
    {
        foreach ($migrations as $migration) {
            // Check if migration exists in migrations table
            $exists = DB::table('migrations')
                ->where('migration', $migration)
                ->exists();
                
            // If not, add it as completed
            if (!$exists) {
                DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => DB::table('migrations')->max('batch') + 1
                ]);
            }
        }
    }
};


===== FILE: 2024_03_20_000002_create_property_media_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
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

    public function down()
    {
        Schema::dropIfExists('property_media');
    }
};


===== FILE: 2024_01_21_000000_create_tables_sequence.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create Users table first if it doesn't exist
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Create Properties table first since other tables depend on it
        if (!Schema::hasTable('properties')) {
            Schema::create('properties', function (Blueprint $table) {
                $table->id();
                $table->string('property_name');
                $table->string('compound_name')->nullable();
                $table->string('property_number')->nullable();
                $table->string('unit_no')->nullable();
                $table->enum('unit_for', ['sale', 'rent']);
                $table->enum('type', ['apartment', 'villa', 'duplex', 'penthouse', 'studio', 'office', 'retail', 'land']);
                $table->string('phase')->nullable();
                $table->string('building')->nullable();
                $table->string('floor')->nullable();
                $table->boolean('finished')->default(true);
                $table->decimal('total_area', 10, 2);
                $table->decimal('unit_area', 10, 2)->nullable();
                $table->decimal('land_area', 10, 2)->nullable();
                $table->decimal('garden_area', 10, 2)->nullable();
                $table->decimal('space_earth', 10, 2)->nullable();
                $table->integer('rooms')->nullable();
                $table->integer('bathrooms')->nullable();
                $table->json('amenities')->nullable();
                $table->string('location_type')->nullable();
                $table->string('category')->nullable();
                $table->string('status')->default('available');
                $table->decimal('total_price', 12, 2);
                $table->decimal('price_per_meter', 12, 2)->nullable();
                $table->string('currency')->default('EGP');
                $table->date('rent_from')->nullable();
                $table->date('rent_to')->nullable();
                $table->string('property_offered_by')->nullable();
                $table->string('owner_name')->nullable();
                $table->string('owner_mobile')->nullable();
                $table->string('owner_tel')->nullable();
                $table->string('contact_status')->nullable();
                $table->foreignId('handler_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('sales_person_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('sales_category')->nullable();
                $table->text('sales_notes')->nullable();
                $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
                $table->text('description')->nullable();
                $table->json('features')->nullable();
                $table->timestamp('last_follow_up')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Create Projects table
        if (!Schema::hasTable('projects')) {
            Schema::create('projects', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('location')->nullable();
                $table->string('developer')->nullable();
                $table->string('status')->nullable();
                $table->date('launch_date')->nullable();
                $table->date('completion_date')->nullable();
                $table->string('featured_image')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Skip creating properties table as it's handled in a separate migration

        // Create Property Media table after properties table exists
        if (!Schema::hasTable('property_media')) {
            Schema::create('property_media', function (Blueprint $table) {
                $table->id();
                $table->foreignId('property_id')->constrained()->cascadeOnDelete();
                $table->string('file_path');
                $table->boolean('is_featured')->default(false);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // Create Leads table after properties table exists
        if (!Schema::hasTable('leads')) {
            Schema::create('leads', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('mobile')->nullable();
                $table->string('status')->default('new');
                $table->string('source')->nullable();
                $table->decimal('budget', 15, 2)->nullable();
                $table->foreignId('property_interest')->nullable()->constrained('properties')->nullOnDelete();
                $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('last_modified_by')->nullable()->constrained('users')->nullOnDelete();
                $table->text('notes')->nullable();
                $table->string('lead_class')->nullable();
                $table->timestamp('last_follow_up')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Create Opportunities table last
        if (!Schema::hasTable('opportunities')) {
            Schema::create('opportunities', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
                $table->string('status')->default('new');
                $table->decimal('value', 12, 2)->nullable();
                $table->decimal('probability', 5, 2)->nullable();
                $table->date('expected_close_date')->nullable();
                $table->text('description')->nullable();
                $table->text('notes')->nullable();
                $table->string('source')->nullable();
                $table->string('stage')->nullable();
                $table->string('type')->nullable();
                $table->string('priority')->nullable();
                $table->timestamp('last_activity_at')->nullable();
                $table->foreignId('last_modified_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        // Drop tables in reverse order of dependencies
        Schema::dropIfExists('opportunities');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('property_media');
        Schema::dropIfExists('properties');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('users');
    }
};


===== FILE: 2023_11_15_000004_add_team_id_to_users_table.php =====

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
        // Skip adding team_id if it already exists in the users table
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'team_id')) {
            // First check if teams table exists
            if (!Schema::hasTable('teams')) {
                // Create error log
                DB::table('migrations_errors')->insert([
                    'migration' => '2023_11_15_000004_add_team_id_to_users_table',
                    'error' => 'Teams table does not exist. Cannot add foreign key.',
                    'created_at' => now()
                ]);
                
                // Add team_id without foreign key
                Schema::table('users', function (Blueprint $table) {
                    $table->unsignedBigInteger('team_id')->nullable();
                });
                
                return;
            }

            // Add team_id with foreign key
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->constrained()->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'team_id')) {
            Schema::table('users', function (Blueprint $table) {
                // Drop foreign key if it exists
                try {
                    $table->dropForeign(['team_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, continue
                }
                
                $table->dropColumn('team_id');
            });
        }
    }
};


===== FILE: 2023_08_15_000001_create_activity_logs_table.php =====

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


===== FILE: 2023_10_10_000003_create_activity_logs_table.php =====

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
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('action');
                $table->string('entity'); // Ensure this column is created
                $table->string('entity_type'); // Ensure this column is created
                $table->integer('entity_id');
                $table->timestamp('timestamp')->useCurrent();
                $table->timestamps();
            });
        } else {
            // Update existing table structure if needed
            Schema::table('activity_logs', function (Blueprint $table) {
                // Add any missing columns that should be added to existing table
                if (!Schema::hasColumn('activity_logs', 'entity')) {
                    $table->string('entity')->after('action');
                }
                if (!Schema::hasColumn('activity_logs', 'entity_type')) {
                    $table->string('entity_type')->after('entity');
                }
                if (!Schema::hasColumn('activity_logs', 'timestamp')) {
                    $table->timestamp('timestamp')->useCurrent()->after('entity_id');
                }
                if (!Schema::hasColumn('activity_logs', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    public function down()
    {
        // Don't drop the table if it was pre-existing
        if (Schema::hasTable('activity_logs')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                // Remove only columns we added
                $table->dropColumnIfExists('entity');
                $table->dropColumnIfExists('entity_type');
                $table->dropColumnIfExists('timestamp');
                // Don't drop timestamps if they were pre-existing
            });
        }
    }
};


===== FILE: 2024_03_10_000000_cleanup_and_fix_migrations.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Create migrations_errors table if it doesn't exist (centralized location)
        if (!Schema::hasTable('migrations_errors')) {
            Schema::create('migrations_errors', function (Blueprint $table) {
                $table->id();
                $table->string('migration');
                $table->text('error');
                $table->timestamp('created_at');
            });
        }

        // Log that we're running a cleanup
        DB::table('migrations_errors')->insert([
            'migration' => '2024_03_10_000000_cleanup_and_fix_migrations',
            'error' => 'Running cleanup migration to fix database structure',
            'created_at' => now()
        ]);

        // Ensure leads table is properly structured
        if (!Schema::hasTable('leads')) {
            // Skip - other migrations should handle this
            DB::table('migrations_errors')->insert([
                'migration' => '2024_03_10_000000_cleanup_and_fix_migrations',
                'error' => 'Leads table is missing, skipping related fixes',
                'created_at' => now()
            ]);
        }

        // Check for duplicate migrations
        $migrationCount = DB::table('migrations')
            ->select('migration')
            ->groupBy('migration')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($migrationCount->count() > 0) {
            foreach ($migrationCount as $duplicateMigration) {
                // Log duplicate
                DB::table('migrations_errors')->insert([
                    'migration' => '2024_03_10_000000_cleanup_and_fix_migrations',
                    'error' => 'Found duplicate migration: ' . $duplicateMigration->migration,
                    'created_at' => now()
                ]);

                // Remove duplicates (keep the oldest one)
                $ids = DB::table('migrations')
                    ->where('migration', $duplicateMigration->migration)
                    ->orderBy('id', 'desc')
                    ->skip(1)
                    ->take(PHP_INT_MAX)
                    ->pluck('id');

                DB::table('migrations')->whereIn('id', $ids)->delete();
            }
        }
    }

    public function down()
    {
        // No down migration needed for cleanup
    }
};


===== FILE: 2024_05_10_000000_add_is_published_to_properties_table.php =====

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
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'is_published')) {
                $table->boolean('is_published')->default(true)->after('is_featured');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'is_published')) {
                $table->dropColumn('is_published');
            }
        });
    }
};


===== FILE: 2024_03_20_000000_create_sessions_table.php =====

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
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

    public function down()
    {
        Schema::dropIfExists('sessions');
    }
};

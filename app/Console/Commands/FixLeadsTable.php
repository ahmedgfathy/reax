<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixLeadsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:leads-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix leads table structure by adding missing columns';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Fixing leads table...');

        // Get current columns
        $columns = Schema::getColumnListing('leads');
        $this->info('Current columns: ' . implode(', ', $columns));

        // List of columns we need to add
        $columnsToAdd = [
            'mobile' => [
                'after' => 'phone',
                'definition' => 'VARCHAR(20) NULL',
            ],
            'lead_status' => [
                'after' => 'status',
                'definition' => 'VARCHAR(255) NULL',
            ],
            'lead_source' => [
                'after' => 'source',
                'definition' => 'VARCHAR(255) NULL',
            ],
            'description' => [
                'after' => 'notes',
                'definition' => 'TEXT NULL',
            ],
            'last_follow_up' => [
                'after' => 'budget',
                'definition' => 'TIMESTAMP NULL',
            ],
            'agent_follow_up' => [
                'after' => 'last_follow_up',
                'definition' => 'TINYINT(1) NOT NULL DEFAULT 0',
            ],
            'lead_class' => [
                'after' => 'agent_follow_up',
                'definition' => 'VARCHAR(50) NULL',
            ],
            'type_of_request' => [
                'after' => 'lead_class',
                'definition' => 'VARCHAR(100) NULL',
            ],
            'last_modified_by' => [
                'after' => 'assigned_to',
                'definition' => 'BIGINT UNSIGNED NULL',
            ],
        ];

        // Add each missing column
        foreach ($columnsToAdd as $column => $info) {
            if (!in_array($column, $columns)) {
                $this->info("Adding $column column...");
                try {
                    $sql = "ALTER TABLE leads ADD COLUMN $column {$info['definition']}";
                    if (isset($info['after'])) {
                        $sql .= " AFTER {$info['after']}";
                    }
                    DB::statement($sql);
                    $this->info("$column column added.");
                } catch (\Exception $e) {
                    $this->error("Error adding $column column: " . $e->getMessage());
                    // Continue with other columns
                }
            } else {
                $this->info("Column $column already exists.");
            }
        }

        // Add foreign key for last_modified_by if it's missing
        if (in_array('last_modified_by', $columns)) {
            $this->info('Checking foreign key for last_modified_by column...');
            try {
                $constraints = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'leads' 
                    AND COLUMN_NAME = 'last_modified_by' 
                    AND REFERENCED_TABLE_NAME = 'users'
                ");
                
                if (empty($constraints)) {
                    $this->info('Adding foreign key for last_modified_by...');
                    DB::statement("
                        ALTER TABLE leads 
                        ADD CONSTRAINT leads_last_modified_by_foreign 
                        FOREIGN KEY (last_modified_by) REFERENCES users(id) 
                        ON DELETE SET NULL
                    ");
                    $this->info('Foreign key added.');
                } else {
                    $this->info('Foreign key for last_modified_by already exists.');
                }
            } catch (\Exception $e) {
                $this->error('Error adding foreign key: ' . $e->getMessage());
                // Continue execution
            }
        }

        // Final status
        $columns = Schema::getColumnListing('leads');
        $this->info('Final leads table columns: ' . implode(', ', $columns));
        $this->info('Leads table structure fix completed!');
        return Command::SUCCESS;
    }
}

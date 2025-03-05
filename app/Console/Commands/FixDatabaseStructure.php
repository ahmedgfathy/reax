<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixDatabaseStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix database structure issues including activity logs and leads tables';

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
        $this->info('Starting database structure fix...');

        $this->info('Step 1: Fixing activity_logs table...');
        $activityLogStatus = $this->call('fix:activity-logs');

        $this->info('Step 2: Fixing leads table...');
        $leadsStatus = $this->call('fix:leads-table');

        if ($activityLogStatus === Command::SUCCESS && $leadsStatus === Command::SUCCESS) {
            $this->info('Database structure fix completed successfully!');
            return Command::SUCCESS;
        } else {
            $this->error('Database structure fix completed with issues. Check the logs for details.');
            return Command::FAILURE;
        }
    }
}

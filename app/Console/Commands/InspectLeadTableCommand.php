<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InspectLeadTableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:inspect-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display the structure of the leads table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Schema::hasTable('leads')) {
            $this->error('The leads table does not exist!');
            return Command::FAILURE;
        }

        $columns = Schema::getColumnListing('leads');
        
        $this->info('Columns in the leads table:');
        $this->table(
            ['Column Name', 'Type', 'Nullable'],
            collect($columns)->map(function ($column) {
                $type = DB::connection()->getDoctrineColumn('leads', $column)->getType()->getName();
                $nullable = DB::connection()->getDoctrineColumn('leads', $column)->getNotnull() ? 'No' : 'Yes';
                
                return [$column, $type, $nullable];
            })
        );

        return Command::SUCCESS;
    }
}

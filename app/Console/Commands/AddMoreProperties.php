<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\PropertiesTableSeeder;

class AddMoreProperties extends Command
{
    protected $signature = 'properties:add {count=30}';
    protected $description = 'Add more property listings to the system';

    public function handle()
    {
        $count = $this->argument('count');

        $this->info("Adding {$count} additional properties to the database...");
        
        try {
            // Run the PropertiesTableSeeder - it will add properties if needed
            $seeder = new PropertiesTableSeeder();
            $seeder->setCommand($this);
            $seeder->run();
            
            $this->info('Properties seeded successfully!');
            $this->info('To view them, visit the properties section in your CRM dashboard.');
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

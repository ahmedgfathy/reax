<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateLeadsTable extends Command
{
    protected $signature = 'create:leads-table';
    protected $description = 'Create the leads table manually';

    public function handle()
    {
        $this->info('Creating leads table...');

        if (DB::statement("SHOW TABLES LIKE 'leads'")->count() > 0) {
            $this->info('Leads table already exists.');
            return;
        }

        DB::statement("
            CREATE TABLE `leads` (
                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                `first_name` varchar(255) NOT NULL,
                `last_name` varchar(255) NOT NULL,
                `email` varchar(255) DEFAULT NULL,
                `phone` varchar(255) DEFAULT NULL,
                `status` enum('new','contacted','qualified','proposal','negotiation','won','lost') NOT NULL DEFAULT 'new',
                `source` varchar(255) DEFAULT NULL,
                `property_interest` bigint unsigned DEFAULT NULL,
                `budget` decimal(12,2) DEFAULT NULL,
                `notes` text,
                `assigned_to` bigint unsigned DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `leads_property_interest_foreign` (`property_interest`),
                KEY `leads_assigned_to_foreign` (`assigned_to`),
                CONSTRAINT `leads_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
                CONSTRAINT `leads_property_interest_foreign` FOREIGN KEY (`property_interest`) REFERENCES `properties` (`id`) ON DELETE SET NULL
            )
        ");

        $this->info('Leads table created successfully.');
    }
}

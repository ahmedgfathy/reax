<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AppwriteService;
use App\Models\Property;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class MigrateAreaDataFromAppwrite extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'appwrite:migrate-area-data {--dry-run : Run migration in dry-run mode without saving data} {--limit=100 : Number of properties to process per batch}';

    /**
     * The console command description.
     */
    protected $description = 'Fast migration of area data only from Appwrite to MariaDB (skips images and heavy processing)';

    protected $defaultCompany;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $batchLimit = (int) $this->option('limit');

        $this->info('Starting fast area-only migration from Appwrite...');
        
        if ($isDryRun) {
            $this->warn('Running in DRY-RUN mode - no data will be saved to database');
        }

        try {
            // Resolve services only when needed
            $appwriteService = app(AppwriteService::class);
            $this->defaultCompany = $this->getOrCreateDefaultCompany();
            
            // Test Appwrite connection
            $this->info('Testing Appwrite connection...');
            if (!$this->testConnection($appwriteService)) {
                $this->error('Failed to connect to Appwrite. Please check your configuration.');
                return Command::FAILURE;
            }

            $this->info('Connection successful! Starting area data migration...');

            // Get total count
            $totalResponse = $appwriteService->getAllProperties(1, 0);
            $totalCount = $totalResponse['total'] ?? 0;
            
            $this->info("Found {$totalCount} properties in Appwrite");

            if ($totalCount === 0) {
                $this->warn('No properties found in Appwrite');
                return Command::SUCCESS;
            }

            // Process in batches
            $offset = 0;
            $totalMigrated = 0;
            $totalSkipped = 0;
            $totalErrors = 0;

            $progressBar = $this->output->createProgressBar($totalCount);
            $progressBar->start();

            while ($offset < $totalCount) {
                $response = $appwriteService->getAllProperties($batchLimit, $offset);
                $properties = $response['documents'] ?? [];

                if (empty($properties)) {
                    break;
                }

                foreach ($properties as $appwriteProperty) {
                    try {
                        $result = $this->migratePropertyAreaData($appwriteProperty, $isDryRun);
                        
                        if ($result === 'migrated') {
                            $totalMigrated++;
                        } elseif ($result === 'skipped') {
                            $totalSkipped++;
                        }
                        
                    } catch (Exception $e) {
                        $totalErrors++;
                        Log::error('Failed to migrate property area data: ' . $appwriteProperty['$id'] . ' - ' . $e->getMessage());
                        
                        if (!$isDryRun) {
                            $this->error("Error migrating property {$appwriteProperty['$id']}: " . $e->getMessage());
                        }
                    }
                    
                    $progressBar->advance();
                }

                $offset += $batchLimit;
            }

            $progressBar->finish();
            $this->newLine(2);

            // Display results
            $this->info("Migration completed!");
            $this->table(['Status', 'Count'], [
                ['Migrated', $totalMigrated],
                ['Skipped (already exists)', $totalSkipped],
                ['Errors', $totalErrors],
                ['Total Processed', $totalMigrated + $totalSkipped + $totalErrors]
            ]);

            return Command::SUCCESS;

        } catch (Exception $e) {
            $this->error('Migration failed: ' . $e->getMessage());
            Log::error('Area data migration failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Test connection to Appwrite
     */
    private function testConnection(AppwriteService $appwriteService): bool
    {
        try {
            $response = $appwriteService->getAllProperties(1, 0);
            return true;
        } catch (Exception $e) {
            Log::error('Appwrite connection test failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get or create default company
     */
    private function getOrCreateDefaultCompany()
    {
        return Company::firstOrCreate(
            ['name' => 'GloMart Real Estates'],
            [
                'slug' => 'glomart-real-estates',
                'email' => 'info@glomart.com',
                'phone' => '+20100000000',
                'address' => 'Egypt',
                'logo' => null,
                'is_active' => true
            ]
        );
    }

    /**
     * Migrate area data only for a single property
     */
    private function migratePropertyAreaData($appwriteProperty, $isDryRun = false): string
    {
        $appwriteId = $appwriteProperty['$id'];

        // Check if property already exists
        $existingProperty = Property::where('appwrite_id', $appwriteId)->first();
        
        if ($existingProperty) {
            // Update area data for existing property
            if (!$isDryRun) {
                $existingProperty->update($this->extractAreaData($appwriteProperty));
            }
            return 'skipped'; // Consider it as skipped since it already existed
        }

        // Create new property with area data and minimal required fields
        $propertyData = array_merge(
            $this->getMinimalPropertyData($appwriteProperty),
            $this->extractAreaData($appwriteProperty)
        );

        if (!$isDryRun) {
            Property::create($propertyData);
        }

        return 'migrated';
    }

    /**
     * Extract area-related data from Appwrite property
     */
    private function extractAreaData(array $appwriteProperty): array
    {
        return [
            // Area fields - these are the main focus of this migration
            'total_area' => $this->parseNumericValue($appwriteProperty['building'] ?? $appwriteProperty['totalArea'] ?? 0),
            'land_area' => $this->parseNumericValue($appwriteProperty['landArea'] ?? null),
            'unit_area' => $this->parseNumericValue($appwriteProperty['unitArea'] ?? $appwriteProperty['spaceUnit'] ?? null),
            'building_area' => $this->parseNumericValue($appwriteProperty['buildingArea'] ?? $appwriteProperty['building'] ?? null),
            'built_area' => $this->parseNumericValue($appwriteProperty['building'] ?? null),
            'garden_area' => $this->parseNumericValue($appwriteProperty['gardenArea'] ?? null),
            'space_earth' => $this->parseNumericValue($appwriteProperty['spaceEerth'] ?? null),
            'space_unit' => $this->parseNumericValue($appwriteProperty['spaceUnit'] ?? null),
            'space_guard' => $this->parseNumericValue($appwriteProperty['spaceGuard'] ?? null),
        ];
    }

    /**
     * Get minimal required property data (no heavy processing)
     */
    private function getMinimalPropertyData(array $appwriteProperty): array
    {
        return [
            // Required fields for property creation
            'appwrite_id' => $appwriteProperty['$id'],
            'company_id' => $this->defaultCompany->id,
            'property_name' => $appwriteProperty['propertyName'] ?? 
                             ($appwriteProperty['type'] ?? 'Property') . ' in ' . 
                             ($appwriteProperty['compoundName'] ?? $appwriteProperty['area'] ?? 'Unknown Location'),
            'property_number' => $appwriteProperty['propertyNumber'] ?? $this->generatePropertyNumber(),
            'unit_for' => $this->mapUnitFor($appwriteProperty['unitFor'] ?? 'sale'),
            'type' => $appwriteProperty['type'] ?? 'apartment',
            'category' => $this->mapCategory($appwriteProperty['category'] ?? 'residential'),
            'location' => $appwriteProperty['area'] ?? null,
            'compound_name' => $appwriteProperty['compoundName'] ?? $appwriteProperty['area'] ?? null,
            'status' => 'available',
            'currency' => $this->mapCurrency($appwriteProperty['currency'] ?? 'EGP'),
            'total_price' => $this->parseNumericValue($appwriteProperty['totalPrice'] ?? $appwriteProperty['price'] ?? 0),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Parse numeric value safely
     */
    private function parseNumericValue($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        
        // Remove any non-numeric characters except decimal point
        $cleaned = preg_replace('/[^\d.]/', '', (string)$value);
        
        return $cleaned !== '' ? (float)$cleaned : null;
    }

    /**
     * Map unit_for field
     */
    private function mapUnitFor(string $unitFor): string
    {
        $mapping = [
            'sale' => 'sale',
            'rent' => 'rent',
            'both' => 'both',
            'Sale' => 'sale',
            'Rent' => 'rent',
            'Both' => 'both',
        ];
        
        return $mapping[$unitFor] ?? 'sale';
    }

    /**
     * Map category field
     */
    private function mapCategory(string $category): string
    {
        $mapping = [
            'residential' => 'residential',
            'commercial' => 'commercial',
            'administrative' => 'administrative',
            'Residential' => 'residential',
            'Commercial' => 'commercial',
            'Administrative' => 'administrative',
        ];
        
        return $mapping[$category] ?? 'residential';
    }

    /**
     * Map currency field
     */
    private function mapCurrency(string $currency): string
    {
        $mapping = [
            'EGP' => 'EGP',
            'USD' => 'USD',
            'EUR' => 'EUR',
            'egp' => 'EGP',
            'usd' => 'USD',
            'eur' => 'EUR',
        ];
        
        return $mapping[$currency] ?? 'EGP';
    }

    /**
     * Generate a unique property number
     */
    private function generatePropertyNumber(): string
    {
        return 'PROP-' . strtoupper(uniqid());
    }
}

<?php

namespace App\Services;

use App\Models\Property;
use App\Models\Lead;
use App\Models\User;
use App\Models\Project;
use App\Models\Event;
use App\Models\Company;
use App\Models\PropertyMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Exception;

class AppwriteMigrationService
{
    private $appwriteService;
    private $defaultCompany;
    
    public function __construct(AppwriteService $appwriteService)
    {
        $this->appwriteService = $appwriteService;
        $this->defaultCompany = $this->getOrCreateDefaultCompany();
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
     * Migrate all data from Appwrite
     */
    public function migrateAllData($isDryRun = false, $progressCallback = null)
    {
        DB::beginTransaction();
        
        try {
            $this->output("Starting Appwrite to MariaDB migration...\n");
            
            // Migrate in order (dependencies first)
            $this->migrateUsers($isDryRun, $progressCallback);
            $this->migrateProjects($isDryRun, $progressCallback);
            $this->migrateProperties($isDryRun, $progressCallback);
            $this->migrateLeads($isDryRun, $progressCallback);
            $this->migrateEvents($isDryRun, $progressCallback);
            
            DB::commit();
            $this->output("Migration completed successfully!\n");
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Migration failed: ' . $e->getMessage());
            $this->output("Migration failed: " . $e->getMessage() . "\n");
            throw $e;
        }
    }
    
    /**
     * Test connection to Appwrite
     */
    public function testConnection(): bool
    {
        try {
            // Try to get a small sample of properties to test connection
            $response = $this->appwriteService->getAllProperties(1, 0);
            return true;
        } catch (Exception $e) {
            Log::error('Appwrite connection test failed: ' . $e->getMessage());
            echo "Connection error: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * Migrate users from Appwrite
     */
    public function migrateUsers(bool $isDryRun = false, callable $progressCallback = null): int
    {
        $offset = 0;
        $limit = 100;
        $totalMigrated = 0;
        $totalCount = 0;
        
        // First pass to get total count
        $tempOffset = 0;
        do {
            $response = $this->appwriteService->getAllUsers($limit, $tempOffset);
            $users = $response['documents'];
            $totalCount += count($users);
            $tempOffset += $limit;
        } while (count($users) == $limit);
        
        // Reset for actual migration
        $processed = 0;
        
        do {
            $response = $this->appwriteService->getAllUsers($limit, $offset);
            $users = $response['documents'];
            
            foreach ($users as $appwriteUser) {
                try {
                    if (!$isDryRun) {
                        $this->migrateUser($appwriteUser, $isDryRun);
                    }
                    $totalMigrated++;
                    $processed++;
                    
                    if ($progressCallback) {
                        $progressCallback($processed, $totalCount);
                    }
                } catch (Exception $e) {
                    Log::error("Failed to migrate user {$appwriteUser['$id']}: " . $e->getMessage());
                }
            }
            
            $offset += $limit;
        } while (count($users) == $limit);
        
        $this->output("Migrated {$totalMigrated} users\n");
        return $totalMigrated;
    }
    
    /**
     * Migrate single user
     */
    private function migrateUser($appwriteUser, $isDryRun = false)
    {
        $userData = [
            'appwrite_id' => $appwriteUser['$id'],
            'company_id' => $this->defaultCompany->id,
            'name' => $appwriteUser['name'] ?? 'Unknown User',
            'email' => $appwriteUser['email'] ?? 'user' . $appwriteUser['$id'] . '@glomart.com',
            'password' => Hash::make($appwriteUser['password'] ?? 'password123'),
            'phone' => $appwriteUser['phone'] ?? null,
            'role' => $this->mapUserRole($appwriteUser['role'] ?? 'user'),
            'is_active' => true,
            'is_admin' => ($appwriteUser['role'] ?? 'user') === 'admin',
            'created_at' => $this->parseDate($appwriteUser['$createdAt']),
            'updated_at' => $this->parseDate($appwriteUser['$updatedAt']),
        ];
        
        if ($isDryRun) {
            $this->output("Dry run - User data: " . json_encode($userData) . "\n");
            return null;
        }
        
        return User::updateOrCreate(
            ['appwrite_id' => $appwriteUser['$id']],
            $userData
        );
    }
    
    /**
     * Migrate projects from Appwrite
     */
    public function migrateProjects(bool $isDryRun = false, callable $progressCallback = null): int
    {
        $offset = 0;
        $limit = 100;
        $totalMigrated = 0;
        $totalCount = 0;
        
        // First pass to get total count
        $tempOffset = 0;
        do {
            $response = $this->appwriteService->getAllProjects($limit, $tempOffset);
            $projects = $response['documents'];
            $totalCount += count($projects);
            $tempOffset += $limit;
        } while (count($projects) == $limit);
        
        // Reset for actual migration
        $processed = 0;
        
        do {
            $response = $this->appwriteService->getAllProjects($limit, $offset);
            $projects = $response['documents'];
            
            foreach ($projects as $appwriteProject) {
                try {
                    if (!$isDryRun) {
                        $this->migrateProject($appwriteProject, $isDryRun);
                    }
                    $totalMigrated++;
                    $processed++;
                    
                    if ($progressCallback) {
                        $progressCallback($processed, $totalCount);
                    }
                } catch (Exception $e) {
                    Log::error("Failed to migrate project {$appwriteProject['$id']}: " . $e->getMessage());
                }
            }
            
            $offset += $limit;
        } while (count($projects) == $limit);
        
        $this->output("Migrated {$totalMigrated} projects\n");
        return $totalMigrated;
    }
    
    /**
     * Migrate single project
     */
    private function migrateProject($appwriteProject, $isDryRun = false)
    {
        $projectData = [
            'appwrite_id' => $appwriteProject['$id'],
            'company_id' => $this->defaultCompany->id,
            'name' => $appwriteProject['name'] ?? 'Unknown Project',
            'code' => $appwriteProject['code'] ?? strtoupper(substr($appwriteProject['name'] ?? 'UNK', 0, 3)),
            'location' => $appwriteProject['location'] ?? null,
            'description' => $appwriteProject['description'] ?? null,
            'developer' => $appwriteProject['developer'] ?? null,
            'status' => $appwriteProject['status'] ?? 'active',
            'amenities' => is_array($appwriteProject['amenities'] ?? null) ? $appwriteProject['amenities'] : [],
            'features' => is_array($appwriteProject['features'] ?? null) ? $appwriteProject['features'] : [],
            'total_area' => $appwriteProject['totalArea'] ?? null,
            'total_units' => $appwriteProject['totalUnits'] ?? null,
            'start_date' => $this->parseDate($appwriteProject['startDate'] ?? null),
            'completion_date' => $this->parseDate($appwriteProject['completionDate'] ?? null),
            'created_at' => $this->parseDate($appwriteProject['$createdAt']),
            'updated_at' => $this->parseDate($appwriteProject['$updatedAt']),
        ];
        
        if ($isDryRun) {
            $this->output("Dry run - Project data: " . json_encode($projectData) . "\n");
            return null;
        }
        
        return Project::updateOrCreate(
            ['appwrite_id' => $appwriteProject['$id']],
            $projectData
        );
    }
    
    /**
     * Migrate properties from Appwrite
     */
    public function migrateProperties(bool $isDryRun = false, callable $progressCallback = null): int
    {
        $offset = 0;
        $limit = 100;
        $totalMigrated = 0;
        $totalCount = 0;
        
        // First pass to get total count
        $tempOffset = 0;
        do {
            $response = $this->appwriteService->getAllProperties($limit, $tempOffset);
            $properties = $response['documents'];
            $totalCount += count($properties);
            $tempOffset += $limit;
        } while (count($properties) == $limit);
        
        // Reset for actual migration
        $processed = 0;
        
        do {
            $response = $this->appwriteService->getAllProperties($limit, $offset);
            $properties = $response['documents'];
            
            foreach ($properties as $appwriteProperty) {
                try {
                    if (!$isDryRun) {
                        $this->migrateProperty($appwriteProperty, $isDryRun);
                    }
                    $totalMigrated++;
                    $processed++;
                    
                    if ($progressCallback) {
                        $progressCallback($processed, $totalCount);
                    }
                } catch (Exception $e) {
                    Log::error("Failed to migrate property {$appwriteProperty['$id']}: " . $e->getMessage());
                    Log::error("Property data: " . json_encode($appwriteProperty));
                }
            }
            
            $offset += $limit;
        } while (count($properties) == $limit);
        
        $this->output("Migrated {$totalMigrated} properties\n");
        return $totalMigrated;
    }
    
    /**
     * Migrate single property
     */
    private function migrateProperty($appwriteProperty, $isDryRun = false)
    {
        // Find related records
        $handler = null;
        if (!empty($appwriteProperty['users']) && is_array($appwriteProperty['users'])) {
            $handler = User::where('appwrite_id', $appwriteProperty['users'][0])->first();
        }
        
        $project = null;
        if (!empty($appwriteProperty['project'])) {
            $project = Project::where('appwrite_id', $appwriteProperty['project'])->first();
        }
        
        $propertyData = [
            // Core Identity
            'appwrite_id' => $appwriteProperty['$id'],
            'company_id' => $this->defaultCompany->id,
            'project_id' => $project?->id,
            'handler_id' => $handler?->id,
            
            // Basic Information
            'property_name' => $appwriteProperty['propertyName'] ?? $appwriteProperty['type'] . ' in ' . ($appwriteProperty['compoundName'] ?? $appwriteProperty['area'] ?? 'Unknown Location'),
            'compound_name' => $appwriteProperty['compoundName'] ?? $appwriteProperty['area'] ?? null,
            'property_number' => $appwriteProperty['propertyNumber'] ?? $this->generatePropertyNumber(),
            'unit_no' => $appwriteProperty['unitNo'] ?? null,
            'unit_number' => $appwriteProperty['unitNo'] ?? null,
            'unit_for' => $this->mapUnitFor($appwriteProperty['unitFor'] ?? 'sale'),
            'type' => $appwriteProperty['type'] ?? 'apartment',
            'phase' => $appwriteProperty['phase'] ?? null,
            'building' => $appwriteProperty['building'] ?? null,
            'floor' => $appwriteProperty['floor'] ?? null,
            'the_floors' => $appwriteProperty['theFloors'] ?? null,
            'category' => $this->mapCategory($appwriteProperty['category'] ?? 'residential'),
            'finished' => $this->mapFinished($appwriteProperty['finished'] ?? true),
            'finished_type' => $appwriteProperty['finished'] ?? null,
            'is_finished' => $this->mapFinished($appwriteProperty['finished'] ?? true),
            'location_type' => $this->mapLocationType($appwriteProperty['inOrOutSideCompound'] ?? 'inside'),
            'in_or_outside_compound' => $this->mapLocationType($appwriteProperty['inOrOutSideCompound'] ?? 'inside'),
            'location' => $appwriteProperty['area'] ?? null,
            
            // Areas (using building field as primary area since it contains numeric values)
            'total_area' => $this->parseNumericValue($appwriteProperty['building'] ?? $appwriteProperty['totalArea'] ?? 0),
            'land_area' => $this->parseNumericValue($appwriteProperty['landArea'] ?? null),
            'unit_area' => $this->parseNumericValue($appwriteProperty['unitArea'] ?? $appwriteProperty['spaceUnit'] ?? null),
            'building_area' => $this->parseNumericValue($appwriteProperty['buildingArea'] ?? $appwriteProperty['building'] ?? null),
            'built_area' => $this->parseNumericValue($appwriteProperty['building'] ?? null),
            'garden_area' => $this->parseNumericValue($appwriteProperty['gardenArea'] ?? null),
            'space_earth' => $this->parseNumericValue($appwriteProperty['spaceEerth'] ?? null),
            'space_unit' => $this->parseNumericValue($appwriteProperty['spaceUnit'] ?? null),
            'space_guard' => $this->parseNumericValue($appwriteProperty['spaceGuard'] ?? null),
            
            // Specifications
            'rooms' => $appwriteProperty['rooms'] ?? $appwriteProperty['bedrooms'] ?? null,
            'bathrooms' => $appwriteProperty['bathrooms'] ?? null,
            'unit_features' => $appwriteProperty['unitFeatures'] ?? null,
            'features' => is_array($appwriteProperty['features'] ?? null) ? $appwriteProperty['features'] : [],
            'amenities' => is_array($appwriteProperty['amenities'] ?? null) ? $appwriteProperty['amenities'] : [],
            
            // Pricing (handle price units properly)
            'total_price' => $this->convertPrice($appwriteProperty['totalPrice'] ?? $appwriteProperty['price'] ?? 0, $appwriteProperty['currency'] ?? 'EGP'),
            'price_per_meter' => $this->parseNumericValue($appwriteProperty['PricePerMeter'] ?? $appwriteProperty['pricePerMeter'] ?? null),
            'currency' => $this->mapCurrency($appwriteProperty['currency'] ?? 'EGP'),
            'rent_from' => !empty($appwriteProperty['rentFrom']) ? $this->parseDate($appwriteProperty['rentFrom']) : null,
            'rent_to' => !empty($appwriteProperty['rentTo']) ? $this->parseDate($appwriteProperty['rentTo']) : null,
            
            // Payment Information
            'payment_method' => $appwriteProperty['paymentMethod'] ?? null,
            'down_payment' => $this->parseNumericValue($appwriteProperty['downPayment'] ?? null),
            'installment' => $this->parseNumericValue($appwriteProperty['installment'] ?? null),
            'installment_years' => $this->parseNumericValue($appwriteProperty['installmentYears'] ?? null),
            'payed_every' => $appwriteProperty['payedEvery'] ?? null,
            'monthly' => $this->parseNumericValue($appwriteProperty['monthly'] ?? null),
            
            // Owner contact information
            'owner_name' => $appwriteProperty['name'] ?? $appwriteProperty['ownerName'] ?? null,
            'owner_mobile' => $appwriteProperty['mobileNo'] ?? $appwriteProperty['mobile'] ?? null,
            'owner_phone' => $appwriteProperty['tel'] ?? $appwriteProperty['phone'] ?? null,
            'owner_tel' => $appwriteProperty['tel'] ?? null,
            'mobile_no' => $appwriteProperty['mobileNo'] ?? null,
            'tel' => $appwriteProperty['tel'] ?? null,
            'owner_email' => $appwriteProperty['email'] ?? null,
            'contact_status' => $appwriteProperty['contactStatus'] ?? null,
            'owner_contact_status' => $appwriteProperty['ownerContactStatus'] ?? null,
            
            // Sales Information
            'sales_category' => $appwriteProperty['activity'] ?? null,
            'activity' => $appwriteProperty['activity'] ?? null,
            'sales' => $appwriteProperty['sales'] ?? null,
            'handler' => $appwriteProperty['handler'] ?? null,
            'property_offered_by' => $appwriteProperty['propertyOfferedBy'] ?? null,
            'sales_notes' => $appwriteProperty['notes'] ?? $appwriteProperty['note'] ?? null,
            'notes' => $appwriteProperty['notes'] ?? $appwriteProperty['note'] ?? null,
            'status' => $this->mapPropertyStatus($appwriteProperty['status'] ?? 'available'),
            
            // Updates and Follow-ups
            'for_update' => $appwriteProperty['forUpdate'] ?? null,
            'call_update' => $appwriteProperty['callUpdate'] ?? null,
            'last_call_update' => $appwriteProperty['lastCallUpdate'] ?? null,
            'last_follow_up' => !empty($appwriteProperty['lastFollowUp']) ? $this->parseDate($appwriteProperty['lastFollowUp']) : null,
            'last_follow_in' => !empty($appwriteProperty['lastFollowIn']) ? $this->parseDate($appwriteProperty['lastFollowIn']) : null,
            'modified_time' => !empty($appwriteProperty['modifiedTime']) ? $this->parseDate($appwriteProperty['modifiedTime']) : null,
            
            // Publishing and Display
            'is_published' => $appwriteProperty['isPublished'] ?? false,
            'is_featured' => $appwriteProperty['isFeatured'] ?? false,
            'is_in_home' => $appwriteProperty['inHome'] ?? false,
            'is_liked' => $appwriteProperty['liked'] ?? false,
            'purpose' => $appwriteProperty['purpose'] ?? null,
            
            // Sharing
            'is_shared' => $appwriteProperty['isShared'] ?? false,
            'sharing_settings' => is_array($appwriteProperty['sharingSettings'] ?? null) ? $appwriteProperty['sharingSettings'] : null,
            
            // Description and Details
            'description' => $appwriteProperty['description'] ?? null,
            
            // Project reference (string field)
            'project' => $appwriteProperty['project'] ?? null,
            
            // Timestamps
            'created_at' => $this->parseDate($appwriteProperty['$createdAt']),
            'updated_at' => $this->parseDate($appwriteProperty['$updatedAt']),
        ];
        
        if ($isDryRun) {
            $this->output("Dry run - Property data: " . json_encode($propertyData) . "\n");
            return null;
        }
        
        $property = Property::updateOrCreate(
            ['appwrite_id' => $appwriteProperty['$id']],
            $propertyData
        );
        
        // Migrate property images
        $this->migratePropertyImages($property, $appwriteProperty, $isDryRun);
        
        return $property;
    }
    
    /**
     * Migrate property images
     */
    private function migratePropertyImages($property, $appwriteProperty, $isDryRun = false)
    {
        // Handle single property image
        $propertyImage = $appwriteProperty['propertyImage'] ?? null;
        if (!empty($propertyImage) && $propertyImage !== '[]') {
            $this->processPropertyImages($property, $propertyImage, true, $isDryRun);
        }
        
        // Handle multiple property images
        $propertyImages = $appwriteProperty['propertyImages'] ?? null;
        if (!empty($propertyImages) && $propertyImages !== '[]') {
            $this->processPropertyImages($property, $propertyImages, false, $isDryRun);
        }
        
        // Handle videos
        $videos = $appwriteProperty['videos'] ?? null;
        if (!empty($videos) && $videos !== '[]') {
            $this->processPropertyVideos($property, $videos, $isDryRun);
        }
    }

    /**
     * Process property images from various formats
     */
    private function processPropertyImages($property, $images, $isFeatured = false, $isDryRun = false)
    {
        try {
            // Parse JSON string if needed
            if (is_string($images)) {
                $images = json_decode($images, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::warning("Invalid JSON in property images for property {$property->id}: {$images}");
                    return;
                }
            }

            if (!is_array($images) || empty($images)) {
                return;
            }

            foreach ($images as $index => $imageData) {
                if (is_string($imageData)) {
                    // Direct file ID
                    $fileId = $imageData;
                } elseif (is_array($imageData) && isset($imageData['id'])) {
                    // Object with id field
                    $fileId = $imageData['id'];
                } else {
                    continue;
                }

                if (!$isDryRun) {
                    $this->downloadAndSavePropertyImage(
                        $property, 
                        $fileId, 
                        config('appwrite.buckets.properties'),
                        $isFeatured && $index === 0
                    );
                }
            }
        } catch (Exception $e) {
            Log::error("Failed to process property images for property {$property->id}: " . $e->getMessage());
        }
    }

    /**
     * Process property videos from various formats
     */
    private function processPropertyVideos($property, $videos, $isDryRun = false)
    {
        try {
            // Parse JSON string if needed
            if (is_string($videos)) {
                $videos = json_decode($videos, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::warning("Invalid JSON in property videos for property {$property->id}: {$videos}");
                    return;
                }
            }

            if (!is_array($videos) || empty($videos)) {
                return;
            }

            foreach ($videos as $videoData) {
                if (is_string($videoData)) {
                    // Direct file ID
                    $fileId = $videoData;
                } elseif (is_array($videoData) && isset($videoData['id'])) {
                    // Object with id field
                    $fileId = $videoData['id'];
                } else {
                    continue;
                }

                if (!$isDryRun) {
                    $this->downloadAndSavePropertyVideo(
                        $property, 
                        $fileId, 
                        config('appwrite.buckets.properties_videos')
                    );
                }
            }
        } catch (Exception $e) {
            Log::error("Failed to process property videos for property {$property->id}: " . $e->getMessage());
        }
    }
    
    /**
     * Download and save property image
     */
    private function downloadAndSavePropertyImage($property, $fileId, $bucketId, $isFeatured = false)
    {
        try {
            // Get file metadata
            $fileMetadata = $this->appwriteService->getFileMetadata($bucketId, $fileId);
            
            if (!$fileMetadata) {
                Log::warning("File metadata not found for file {$fileId} in bucket {$bucketId}");
                return;
            }
            
            // Download file content
            $fileContent = $this->appwriteService->downloadFile($bucketId, $fileId);
            
            if (!$fileContent) {
                Log::warning("File content not found for file {$fileId} in bucket {$bucketId}");
                return;
            }
            
            // Generate local file path
            $fileName = $fileMetadata['name'];
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $localFileName = $property->id . '_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
            $localPath = "companies/{$property->company_id}/properties/{$localFileName}";
            
            // Save file to local storage
            Storage::disk('public')->put($localPath, $fileContent);
            
            // Create property media record
            PropertyMedia::create([
                'property_id' => $property->id,
                'type' => 'image',
                'file_path' => $localPath,
                'original_name' => $fileName,
                'appwrite_file_id' => $fileId,
                'is_featured' => $isFeatured,
                'sort_order' => 0
            ]);
            
            Log::info("Successfully downloaded and saved property image {$fileId} for property {$property->id}");
            
        } catch (Exception $e) {
            Log::error("Failed to download property image {$fileId}: " . $e->getMessage());
        }
    }
    
    /**
     * Download and save property video
     */
    private function downloadAndSavePropertyVideo($property, $fileId, $bucketId)
    {
        try {
            // Get file metadata
            $fileMetadata = $this->appwriteService->getFileMetadata($bucketId, $fileId);
            
            if (!$fileMetadata) {
                return;
            }
            
            // Download file content
            $fileContent = $this->appwriteService->downloadFile($bucketId, $fileId);
            
            // Generate local file path
            $fileName = $fileMetadata['name'];
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $localFileName = $property->id . '_video_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
            $localPath = "companies/{$property->company_id}/properties/videos/{$localFileName}";
            
            // Save file to local storage
            Storage::disk('public')->put($localPath, $fileContent);
            
            // Create property media record
            PropertyMedia::create([
                'property_id' => $property->id,
                'type' => 'video',
                'file_path' => $localPath,
                'original_name' => $fileName,
                'appwrite_file_id' => $fileId,
                'is_featured' => false,
            ]);
            
        } catch (Exception $e) {
            Log::error("Failed to download property video {$fileId}: " . $e->getMessage());
        }
    }
    
    /**
     * Migrate leads from Appwrite
     */
    public function migrateLeads(bool $isDryRun = false, callable $progressCallback = null): int
    {
        $offset = 0;
        $limit = 100;
        $totalMigrated = 0;
        $totalCount = 0;
        
        // First pass to get total count
        $tempOffset = 0;
        do {
            $response = $this->appwriteService->getAllLeads($limit, $tempOffset);
            $leads = $response['documents'];
            $totalCount += count($leads);
            $tempOffset += $limit;
        } while (count($leads) == $limit);
        
        // Reset for actual migration
        $processed = 0;
        
        do {
            $response = $this->appwriteService->getAllLeads($limit, $offset);
            $leads = $response['documents'];
            
            foreach ($leads as $appwriteLead) {
                try {
                    if (!$isDryRun) {
                        $this->migrateLead($appwriteLead, $isDryRun);
                    }
                    $totalMigrated++;
                    $processed++;
                    
                    if ($progressCallback) {
                        $progressCallback($processed, $totalCount);
                    }
                } catch (Exception $e) {
                    Log::error("Failed to migrate lead {$appwriteLead['$id']}: " . $e->getMessage());
                }
            }
            
            $offset += $limit;
        } while (count($leads) == $limit);
        
        $this->output("Migrated {$totalMigrated} leads\n");
        return $totalMigrated;
    }
    
    /**
     * Migrate single lead
     */
    private function migrateLead($appwriteLead, $isDryRun = false)
    {
        // Find assigned user
        $assignedTo = null;
        if (!empty($appwriteLead['userId']) && is_array($appwriteLead['userId'])) {
            $assignedTo = User::where('appwrite_id', $appwriteLead['userId'][0])->first();
        }
        
        $leadData = [
            'appwrite_id' => $appwriteLead['$id'],
            'company_id' => $this->defaultCompany->id,
            'lead_number' => $appwriteLead['leadNumber'] ?? $this->generateLeadNumber(),
            'first_name' => $appwriteLead['firstName'] ?? $appwriteLead['name'] ?? 'Unknown',
            'last_name' => $appwriteLead['lastName'] ?? '',
            'email' => $appwriteLead['email'] ?? null,
            'phone' => $appwriteLead['phone'] ?? $appwriteLead['mobileNo'] ?? null,
            'mobile' => $appwriteLead['mobileNo'] ?? $appwriteLead['mobile'] ?? null,
            'source' => $appwriteLead['source'] ?? 'website',
            'status' => $this->mapLeadStatus($appwriteLead['status'] ?? 'new'),
            'priority' => $appwriteLead['priority'] ?? 'medium',
            'assigned_to' => $assignedTo?->id,
            'notes' => $appwriteLead['notes'] ?? null,
            'budget_min' => $appwriteLead['budgetMin'] ?? null,
            'budget_max' => $appwriteLead['budgetMax'] ?? null,
            'preferred_location' => $appwriteLead['preferredLocation'] ?? null,
            'property_type' => $appwriteLead['propertyType'] ?? null,
            'follow_up_date' => $this->parseDate($appwriteLead['followUpDate'] ?? null),
            'last_contact_date' => $this->parseDate($appwriteLead['lastContactDate'] ?? null),
            'created_at' => $this->parseDate($appwriteLead['$createdAt']),
            'updated_at' => $this->parseDate($appwriteLead['$updatedAt']),
        ];
        
        if ($isDryRun) {
            $this->output("Dry run - Lead data: " . json_encode($leadData) . "\n");
            return null;
        }
        
        return Lead::updateOrCreate(
            ['appwrite_id' => $appwriteLead['$id']],
            $leadData
        );
    }
    
    /**
     * Migrate events from Appwrite
     */
    public function migrateEvents(bool $isDryRun = false, callable $progressCallback = null): int
    {
        $offset = 0;
        $limit = 100;
        $totalMigrated = 0;
        $totalCount = 0;
        
        // First pass to get total count
        $tempOffset = 0;
        do {
            $response = $this->appwriteService->getAllEvents($limit, $tempOffset);
            $events = $response['documents'];
            $totalCount += count($events);
            $tempOffset += $limit;
        } while (count($events) == $limit);
        
        // Reset for actual migration
        $processed = 0;
        
        do {
            $response = $this->appwriteService->getAllEvents($limit, $offset);
            $events = $response['documents'];
            
            foreach ($events as $appwriteEvent) {
                try {
                    if (!$isDryRun) {
                        $this->migrateEvent($appwriteEvent, $isDryRun);
                    }
                    $totalMigrated++;
                    $processed++;
                    
                    if ($progressCallback) {
                        $progressCallback($processed, $totalCount);
                    }
                } catch (Exception $e) {
                    Log::error("Failed to migrate event {$appwriteEvent['$id']}: " . $e->getMessage());
                }
            }
            
            $offset += $limit;
        } while (count($events) == $limit);
        
        $this->output("Migrated {$totalMigrated} events\n");
        return $totalMigrated;
    }
    
    /**
     * Migrate single event
     */
    private function migrateEvent($appwriteEvent, $isDryRun = false)
    {
        // Find related user
        $user = null;
        if (!empty($appwriteEvent['userId']) && is_array($appwriteEvent['userId'])) {
            $user = User::where('appwrite_id', $appwriteEvent['userId'][0])->first();
        }
        
        // Find related lead by lead number or other identifier
        $lead = null;
        if (!empty($appwriteEvent['leadId'])) {
            $lead = Lead::where('appwrite_id', $appwriteEvent['leadId'])->first();
        }
        
        $eventData = [
            'appwrite_id' => $appwriteEvent['$id'],
            'company_id' => $this->defaultCompany->id,
            'title' => $appwriteEvent['title'] ?? 'Event',
            'description' => $appwriteEvent['description'] ?? null,
            'event_type' => $this->mapEventType($appwriteEvent['eventType'] ?? 'other'),
            'start_date' => $this->parseDate($appwriteEvent['startDate'] ?? $appwriteEvent['date'] ?? now()),
            'end_date' => !empty($appwriteEvent['endDate']) ? $this->parseDate($appwriteEvent['endDate']) : null,
            'status' => $appwriteEvent['status'] ?? 'pending',
            'location' => $appwriteEvent['location'] ?? null,
            'user_id' => $user?->id,
            'lead_id' => $lead?->id,
            'is_completed' => $appwriteEvent['isCompleted'] ?? false,
            'created_at' => $this->parseDate($appwriteEvent['$createdAt']),
            'updated_at' => $this->parseDate($appwriteEvent['$updatedAt']),
        ];
        
        if ($isDryRun) {
            $this->output("Dry run - Event data: " . json_encode($eventData) . "\n");
            return null;
        }
        
        return Event::updateOrCreate(
            ['appwrite_id' => $appwriteEvent['$id']],
            $eventData
        );
    }
    
    // Helper methods for mapping data
    private function mapUserRole($role)
    {
        $roleMap = [
            'admin' => 'admin',
            'manager' => 'manager',
            'teamLead' => 'team_lead',
            'sales' => 'sales',
            'user' => 'employee',
        ];
        
        return $roleMap[$role] ?? 'employee';
    }
    
    private function mapUnitFor($unitFor)
    {
        $unitForMap = [
            'sale' => 'sale',
            'rent' => 'rent',
            'both' => 'both',
        ];
        
        return $unitForMap[$unitFor] ?? 'sale';
    }
    
    private function mapCategory($category)
    {
        $categoryMap = [
            'residential' => 'residential',
            'commercial' => 'commercial',
            'administrative' => 'administrative',
        ];
        
        return $categoryMap[$category] ?? 'residential';
    }
    
    private function mapPropertyStatus($status)
    {
        $statusMap = [
            'available' => 'available',
            'sold' => 'sold',
            'rented' => 'rented',
            'reserved' => 'reserved',
        ];
        
        return $statusMap[$status] ?? 'available';
    }
    
    private function mapLeadStatus($status)
    {
        $statusMap = [
            'new' => 'new',
            'contacted' => 'contacted',
            'qualified' => 'qualified',
            'proposal' => 'proposal',
            'negotiation' => 'negotiation',
            'won' => 'won',
            'lost' => 'lost',
        ];
        
        return $statusMap[$status] ?? 'new';
    }
    
    private function mapEventType($eventType)
    {
        $eventTypeMap = [
            'meeting' => 'meeting',
            'call' => 'call',
            'email' => 'email',
            'birthday' => 'birthday',
            'follow_up' => 'follow_up',
            'other' => 'other',
        ];
        
        return $eventTypeMap[$eventType] ?? 'other';
    }
    
    private function mapFinished($finished)
    {
        // Handle boolean values
        if (is_bool($finished)) {
            return $finished;
        }
        
        // Handle string values that should map to boolean
        if (is_string($finished)) {
            $finishedMap = [
                // Fully finished variations
                'Fully Finished' => true,
                'Fully Finished & Furnished' => true,
                'FULLY FINISHED' => true,
                'fully finished' => true,
                'finished' => true,
                'complete' => true,
                'completed' => true,
                
                // Semi finished variations
                'Semi Finished' => false,
                'SEMI FINISHED' => false,
                'semi finished' => false,
                'semi' => false,
                'unfinished' => false,
                'incomplete' => false,
                
                // Core/Shell variations
                'Core' => false,
                'Shell' => false,
                'core' => false,
                'shell' => false,
                
                // Boolean string representations
                'true' => true,
                '1' => true,
                'yes' => true,
                'false' => false,
                '0' => false,
                'no' => false,
            ];
            
            return $finishedMap[$finished] ?? true; // Default to true (finished)
        }
        
        // Handle numeric values
        if (is_numeric($finished)) {
            return (bool) $finished;
        }
        
        // Default fallback
        return true;
    }
    
    private function parseDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }
        
        try {
            return \Carbon\Carbon::parse($dateString);
        } catch (Exception $e) {
            return null;
        }
    }
    
    private function generatePropertyNumber()
    {
        $lastProperty = Property::orderBy('property_number', 'desc')
            ->where('property_number', 'like', 'PRO%')
            ->first();
            
        if (!$lastProperty) {
            return 'PRO10000000';
        }
        
        $lastNumber = (int) substr($lastProperty->property_number, 3);
        return 'PRO' . ($lastNumber + 1);
    }
    
    private function generateLeadNumber()
    {
        $lastLead = Lead::orderBy('lead_number', 'desc')
            ->where('lead_number', 'like', 'LEA%')
            ->first();
            
        if (!$lastLead) {
            return 'LEA1';
        }
        
        $lastNumber = (int) substr($lastLead->lead_number, 3);
        return 'LEA' . ($lastNumber + 1);
    }
    
    private function output($message)
    {
        echo $message;
        Log::info(trim($message));
    }
    
    /**
     * Safely parse numeric value from mixed input
     */
    private function parseNumericValue($value, $default = 0)
    {
        if ($value === null || $value === '') {
            return $default;
        }
        
        // If it's already numeric, return it
        if (is_numeric($value)) {
            return floatval($value);
        }
        
        // If it's a string, try to extract numeric value
        if (is_string($value)) {
            // Remove any non-numeric characters except decimal point
            $cleaned = preg_replace('/[^\d.]/', '', $value);
            if (is_numeric($cleaned) && $cleaned !== '') {
                return floatval($cleaned);
            }
        }
        
        return $default;
    }
    
    /**
     * Map location type from Appwrite to local database
     */
    private function mapLocationType($locationType)
    {
        if (!$locationType) {
            return 'inside';
        }
        
        $locationType = strtolower(trim($locationType));
        
        switch ($locationType) {
            case 'inside':
            case 'in':
                return 'inside';
            case 'outside':
            case 'out':
            case 'out side':
                return 'outside';
            default:
                return 'inside';
        }
    }

    /**
     * Convert price from Appwrite format to local format
     */
    private function convertPrice($price, $currency = 'EGP')
    {
        $numericPrice = $this->parseNumericValue($price);
        
        // Handle different currency scales
        $currency = strtolower(trim($currency ?? ''));
        
        switch ($currency) {
            case 'dollar':
            case 'usd':
            case '$':
                // Convert millions to actual value if needed
                return $numericPrice >= 1 && $numericPrice <= 100 ? $numericPrice * 1000000 : $numericPrice;
            case 'egpyt':
            case 'egp':
            case 'egyptian pound':
                // Handle Egyptian pound scale
                return $numericPrice >= 1 && $numericPrice <= 1000 ? $numericPrice * 1000 : $numericPrice;
            default:
                return $numericPrice;
        }
    }

    /**
     * Map currency from Appwrite to standard format
     */
    private function mapCurrency($currency)
    {
        if (!$currency) {
            return 'EGP';
        }
        
        $currency = strtolower(trim($currency));
        
        switch ($currency) {
            case 'dollar':
            case 'usd':
            case '$':
                return 'USD';
            case 'egpyt':
            case 'egp':
            case 'egyptian pound':
                return 'EGP';
            case 'euro':
            case 'eur':
            case '€':
                return 'EUR';
            default:
                return 'EGP';
        }
    }
}

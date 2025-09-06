<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Models\PropertyMedia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AssociatePropertyImages extends Command
{
    protected $signature = 'properties:associate-images {--dry-run : Run without making changes}';
    protected $description = 'Associate existing downloaded images with their properties';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        $this->info('Starting property image association process...');
        
        if ($isDryRun) {
            $this->warn('Running in DRY-RUN mode - no changes will be made');
        }
        
        // Get all image files in the properties directory
        $imageDirectory = 'companies/2/properties';
        $imageFiles = Storage::disk('public')->files($imageDirectory);
        
        $this->info('Found ' . count($imageFiles) . ' image files');
        
        // Group images by property ID
        $imagesByProperty = [];
        $unassociatedImages = [];
        
        foreach ($imageFiles as $imagePath) {
            $filename = basename($imagePath);
            
            // Extract property ID from filename (format: propertyId_timestamp_random.extension)
            if (preg_match('/^(\d+)_/', $filename, $matches)) {
                $propertyId = (int)$matches[1];
                
                if (!isset($imagesByProperty[$propertyId])) {
                    $imagesByProperty[$propertyId] = [];
                }
                
                $imagesByProperty[$propertyId][] = [
                    'filename' => $filename,
                    'path' => $imagePath
                ];
            } else {
                $unassociatedImages[] = $imagePath;
            }
        }
        
        $this->info('Grouped images for ' . count($imagesByProperty) . ' properties');
        
        if (!empty($unassociatedImages)) {
            $this->warn('Found ' . count($unassociatedImages) . ' images that could not be associated with properties');
        }
        
        // Process each property
        $processedProperties = 0;
        $totalImagesAssociated = 0;
        $progressBar = $this->output->createProgressBar(count($imagesByProperty));
        
        foreach ($imagesByProperty as $propertyId => $images) {
            $property = Property::find($propertyId);
            
            if (!$property) {
                $this->warn("Property with ID {$propertyId} not found in database");
                continue;
            }
            
            // Check if property already has media associations
            $existingMediaCount = PropertyMedia::where('property_id', $propertyId)->count();
            
            if ($existingMediaCount > 0 && !$isDryRun) {
                $this->warn("Property {$propertyId} already has {$existingMediaCount} media records, skipping");
                continue;
            }
            
            $imageCount = 0;
            foreach ($images as $index => $imageData) {
                $isFeatured = ($index === 0); // First image is featured
                
                if (!$isDryRun) {
                    // Create PropertyMedia record
                    PropertyMedia::create([
                        'property_id' => $propertyId,
                        'type' => 'image',
                        'file_path' => $imageData['path'],
                        'original_name' => $imageData['filename'],
                        'appwrite_file_id' => null, // We don't have this for existing files
                        'is_featured' => $isFeatured,
                        'sort_order' => $index
                    ]);
                }
                
                $imageCount++;
                $totalImagesAssociated++;
            }
            
            $processedProperties++;
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine();
        
        if ($isDryRun) {
            $this->info("DRY-RUN RESULTS:");
            $this->info("- Would associate {$totalImagesAssociated} images");
            $this->info("- Would process {$processedProperties} properties");
        } else {
            $this->info("COMPLETED:");
            $this->info("- Associated {$totalImagesAssociated} images");
            $this->info("- Processed {$processedProperties} properties");
        }
        
        // Show summary of properties with and without images
        $propertiesWithImages = Property::has('media')->count();
        $propertiesWithoutImages = Property::doesntHave('media')->count();
        
        $this->newLine();
        $this->info("SUMMARY:");
        $this->info("- Properties with images: {$propertiesWithImages}");
        $this->info("- Properties without images: {$propertiesWithoutImages}");
        
        return 0;
    }
}

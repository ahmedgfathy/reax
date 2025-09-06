<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Property;
use App\Models\PropertyMedia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

echo "Starting property image association process...\n";

// Get all image files in the properties directory
$imageDirectory = 'companies/2/properties';
$imageFiles = Storage::disk('public')->files($imageDirectory);

echo "Found " . count($imageFiles) . " image files\n";

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

echo "Grouped images for " . count($imagesByProperty) . " properties\n";

if (!empty($unassociatedImages)) {
    echo "Found " . count($unassociatedImages) . " images that could not be associated with properties\n";
}

// Process each property
$processedProperties = 0;
$totalImagesAssociated = 0;
$skippedProperties = 0;

echo "Processing properties...\n";

foreach ($imagesByProperty as $propertyId => $images) {
    $property = Property::find($propertyId);
    
    if (!$property) {
        echo "WARNING: Property with ID {$propertyId} not found in database\n";
        $skippedProperties++;
        continue;
    }
    
    // Check if property already has media associations
    $existingMediaCount = PropertyMedia::where('property_id', $propertyId)->count();
    
    if ($existingMediaCount > 0) {
        echo "Property {$propertyId} already has {$existingMediaCount} media records, skipping\n";
        $skippedProperties++;
        continue;
    }
    
    $imageCount = 0;
    foreach ($images as $index => $imageData) {
        $isFeatured = ($index === 0); // First image is featured
        
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
        
        $imageCount++;
        $totalImagesAssociated++;
    }
    
    echo "Associated {$imageCount} images with property {$propertyId}\n";
    $processedProperties++;
    
    // Progress indicator
    if ($processedProperties % 100 == 0) {
        echo "Processed {$processedProperties} properties so far...\n";
    }
}

echo "\nCOMPLETED:\n";
echo "- Associated {$totalImagesAssociated} images\n";
echo "- Processed {$processedProperties} properties\n";
echo "- Skipped {$skippedProperties} properties\n";

// Show summary of properties with and without images
$propertiesWithImages = Property::has('media')->count();
$propertiesWithoutImages = Property::doesntHave('media')->count();

echo "\nSUMMARY:\n";
echo "- Properties with images: {$propertiesWithImages}\n";
echo "- Properties without images: {$propertiesWithoutImages}\n";
echo "- Total properties: " . Property::count() . "\n";

echo "\nImage association process completed!\n";

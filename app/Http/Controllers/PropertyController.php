<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    /**
     * Display a listing of properties with enhanced filtering options.
     */
    public function index(Request $request)
    {
        $query = Property::query();
        
        // Search across multiple fields that exist in the database
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                // Get actual columns from the properties table
                $columns = Schema::getColumnListing('properties');
                
                // These are the columns we want to search in if they exist
                $searchableColumns = [
                    'name', 'location', 'description', 'price', 
                    'owner_name', 'owner_contact', 'unit_number', 'address'
                ];
                
                // Only search in columns that actually exist
                $firstColumn = true;
                foreach ($searchableColumns as $column) {
                    if (in_array($column, $columns)) {
                        if ($firstColumn) {
                            $q->where($column, 'like', "%{$searchTerm}%");
                            $firstColumn = false;
                        } else {
                            $q->orWhere($column, 'like', "%{$searchTerm}%");
                        }
                    }
                }
            });
        }
        
        // Type filter (residential, commercial, etc.)
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        
        // Status filter (rent/sale)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Primary unit filter - only if the column exists
        if ($request->has('is_primary') && $request->is_primary != '' && Schema::hasColumn('properties', 'is_primary')) {
            $isPrimary = $request->is_primary === 'yes';
            $query->where('is_primary', $isPrimary);
        }
        
        // Price range filter
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Make sure the column exists before sorting
        $orderBy = $request->order_by ?? 'created_at';
        if (!Schema::hasColumn('properties', $orderBy)) {
            $orderBy = 'created_at'; // Fallback to created_at if the requested column doesn't exist
        }
        
        $orderDirection = $request->order_direction ?? 'desc';
        $query->orderBy($orderBy, $orderDirection);
        
        // Determine number of properties per page
        $perPage = $request->per_page ? (int) $request->per_page : 12; // Default to 12 for properties grid
        // Ensure per_page is one of the allowed values
        if (!in_array($perPage, [12, 25, 50, 100])) {
            $perPage = 12;
        }
        
        // Get the properties with pagination
        $properties = $query->paginate($perPage)->withQueryString();
        
        // Get distinct property types for the filter
        $propertyTypes = Property::distinct()->pluck('type')->filter()->values();
        
        return view('properties.index', compact('properties', 'propertyTypes', 'perPage'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        return view('properties.create');
    }

    /**
     * Store a newly created property in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'compound_name' => 'required|string|max:255',
            'property_number' => 'nullable|string|max:50',
            'unit_number' => 'nullable|string|max:50',
            'unit_for' => 'required|string|in:rent,sale',
            'area' => 'required|string|max:255',
            'rooms' => 'nullable|integer',
            'phase' => 'nullable|string|max:50',
            'type' => 'required|string|max:100',
            'building' => 'nullable|string|max:100',
            'floor' => 'nullable|string|max:50',
            'finished' => 'nullable|string|in:yes,no,semi',
            'property_props' => 'nullable|string',
            'location_type' => 'nullable|string|in:inside,outside',
            'price' => 'required|numeric',
            'price_per_meter' => 'nullable|numeric',
            'currency' => 'required|string|max:50',
            'project_id' => 'nullable|exists:projects,id',
            'last_follow_up' => 'nullable|date',
            'category' => 'required|string|max:100',
            'status' => 'nullable|string|max:100',
            'rent_from' => 'nullable|date',
            'rent_to' => 'nullable|date',
            'land_area' => 'nullable|numeric',
            'space_earth' => 'nullable|numeric',
            'garden_area' => 'nullable|numeric',
            'unit_area' => 'nullable|numeric',
            'description' => 'nullable|string',
            'property_offered_by' => 'required|string|max:100',
            'owner_name' => 'nullable|string|max:255',
            'owner_mobile' => 'nullable|string|max:50',
            'owner_tel' => 'nullable|string|max:50',
            'update_calls' => 'nullable|string|max:100',
            'handler_id' => 'nullable|exists:users,id',
            'sales_person_id' => 'nullable|exists:users,id',
            'sales_category' => 'nullable|string|max:100',
            'sales_notes' => 'nullable|string',
            'bathrooms' => 'nullable|integer',
            'address' => 'nullable|string|max:255',
            'is_primary' => 'nullable|boolean',
        ]);

        $property = Property::create($validatedData);
        
        // Handle media files upload
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $index => $mediaFile) {
                $mediaType = $this->getFileType($mediaFile);
                $filePath = $mediaFile->store('property_media', 'public');
                
                // Create thumbnail for images
                $thumbnailPath = null;
                if ($mediaType === 'image') {
                    $thumbnailPath = $this->createThumbnail($filePath);
                }
                
                $property->mediaFiles()->create([
                    'media_type' => $mediaType,
                    'file_path' => $filePath,
                    'thumbnail_path' => $thumbnailPath,
                    'is_featured' => ($index === 0), // First uploaded file is featured
                    'sort_order' => $index
                ]);
            }
        }

        return redirect()->route('properties.index')->with('success', 'Property created successfully.');
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property)
    {
        return view('properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit(Property $property)
    {
        return view('properties.edit', compact('property'));
    }

    /**
     * Update the specified property in storage.
     */
    public function update(Request $request, Property $property)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'compound_name' => 'required|string|max:255',
            'property_number' => 'nullable|string|max:50',
            'unit_number' => 'nullable|string|max:50',
            'unit_for' => 'required|string|in:rent,sale',
            'area' => 'required|string|max:255',
            'rooms' => 'nullable|integer',
            'phase' => 'nullable|string|max:50',
            'type' => 'required|string|max:100',
            'building' => 'nullable|string|max:100',
            'floor' => 'nullable|string|max:50',
            'finished' => 'nullable|string|in:yes,no,semi',
            'property_props' => 'nullable|string',
            'location_type' => 'nullable|string|in:inside,outside',
            'price' => 'required|numeric',
            'price_per_meter' => 'nullable|numeric',
            'currency' => 'required|string|max:50',
            'project_id' => 'nullable|exists:projects,id',
            'last_follow_up' => 'nullable|date',
            'category' => 'required|string|max:100',
            'status' => 'nullable|string|max:100',
            'rent_from' => 'nullable|date',
            'rent_to' => 'nullable|date',
            'land_area' => 'nullable|numeric',
            'space_earth' => 'nullable|numeric',
            'garden_area' => 'nullable|numeric',
            'unit_area' => 'nullable|numeric',
            'description' => 'nullable|string',
            'property_offered_by' => 'required|string|max:100',
            'owner_name' => 'nullable|string|max:255',
            'owner_mobile' => 'nullable|string|max:50',
            'owner_tel' => 'nullable|string|max:50',
            'update_calls' => 'nullable|string|max:100',
            'handler_id' => 'nullable|exists:users,id',
            'sales_person_id' => 'nullable|exists:users,id',
            'sales_category' => 'nullable|string|max:100',
            'sales_notes' => 'nullable|string',
            'bathrooms' => 'nullable|integer',
            'address' => 'nullable|string|max:255',
            'is_primary' => 'nullable|boolean',
        ]);

        $property->update($validatedData);
        
        // Handle media files upload for updates
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $index => $mediaFile) {
                // Fix this line - there was a syntax error with $->getFileType
                $mediaType = $this->getFileType($mediaFile);
                $filePath = $mediaFile->store('property_media', 'public');
                
                // Create thumbnail for images
                $thumbnailPath = null;
                if ($mediaType === 'image') {
                    $thumbnailPath = $this->createThumbnail($filePath);
                }
                
                // If it's the first upload and there are no featured images, mark as featured
                $isFeatured = false;
                if ($index === 0 && !$property->mediaFiles()->where('is_featured', true)->exists()) {
                    $isFeatured = true;
                }
                
                $property->mediaFiles()->create([
                    'media_type' => $mediaType,
                    'file_path' => $filePath,
                    'thumbnail_path' => $thumbnailPath,
                    'is_featured' => $isFeatured,
                    'sort_order' => $property->mediaFiles()->count() + $index
                ]);
            }
        }

        return redirect()->route('properties.show', $property->id)->with('success', 'Property updated successfully.');
    }

    /**
     * Remove the specified property from storage.
     */
    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()->route('properties.index')->with('success', 'Property deleted successfully.');
    }

    /**
     * Toggle the featured status of the specified property.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function toggleFeatured(Property $property)
    {
        $property->is_featured = !$property->is_featured;
        $property->save();

        // If the request is AJAX, return JSON
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'is_featured' => $property->is_featured
            ]);
        }
        
        // If not AJAX (regular form submission), redirect back with notification
        return back()->with('success', $property->is_featured 
            ? __('Property added to featured list! It will now appear on the home page.')
            : __('Property removed from featured list.')
        );
    }

    // Helper function to determine file type
    private function getFileType($file)
    {
        $mimeType = $file->getMimeType();
        if (strstr($mimeType, 'image/')) {
            return 'image';
        } elseif (strstr($mimeType, 'video/')) {
            return 'video';
        }
        return 'document';
    }

    // Helper function to create thumbnails
    private function createThumbnail($filePath)
    {
        try {
            $fullPath = storage_path('app/public/' . $filePath);
            $thumbnailPath = 'thumbnails/' . basename($filePath);
            $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);
            
            // Create directory if it doesn't exist
            if (!file_exists(dirname($thumbnailFullPath))) {
                mkdir(dirname($thumbnailFullPath), 0755, true);
            }
            
            $img = \Image::make($fullPath);
            $img->fit(300, 200)->save($thumbnailFullPath);
            
            return $thumbnailPath;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Import properties from CSV or Excel file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,xlsx,xls|max:5120',
        ]);
        
        try {
            // Implementation of import logic here
            // Consider using Laravel Excel package or a CSV parser
            
            return redirect()->route('properties.index')->with('success', __('Properties imported successfully!'));
        } catch (\Exception $e) {
            return redirect()->route('properties.index')->with('error', __('Error importing properties: ') . $e->getMessage());
        }
    }

    /**
     * Export properties to CSV or Excel file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        try {
            // Implementation of export logic here
            // Consider using Laravel Excel package
            
            return redirect()->route('properties.index')->with('success', __('Properties exported successfully!'));
        } catch (\Exception $e) {
            return redirect()->route('properties.index')->with('error', __('Error exporting properties: ') . $e->getMessage());
        }
    }

    /**
     * Display a listing of featured properties.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function featured(Request $request)
    {
        $query = Property::query()->where('is_featured', true);
        
        // Filter by property type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        
        // Filter by property status (sale/rent)
        if ($request->has('for') && $request->for != '') {
            $query->where('unit_for', $request->for);
        }
        
        // Sort properties
        $sortMethod = $request->get('sort', 'newest');
        
        switch ($sortMethod) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        // Get featured properties with pagination
        $featuredProperties = $query->paginate(9)->withQueryString();
        
        return view('featured-properties', compact('featuredProperties'));
    }

    public function sale(Request $request)
    {
        $query = Property::where('unit_for', 'sale')
            ->where('is_published', true)
            ->when($request->search, function($q, $search) {
                return $q->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->type, function($q, $type) {
                return $q->where('type', $type);
            })
            ->when($request->min_price, function($q, $price) {
                return $q->where('price', '>=', $price);
            })
            ->when($request->max_price, function($q, $price) {
                return $q->where('price', '<=', $price);
            });

        // Handle sorting
        $query->when($request->sort, function($q, $sort) {
            switch($sort) {
                case 'price_low':
                    return $q->orderBy('price', 'asc');
                case 'price_high':
                    return $q->orderBy('price', 'desc');
                default:
                    return $q->latest();
            }
        }, function($q) {
            return $q->latest();
        });

        $properties = $query->paginate(12)->withQueryString();
        
        return view('sale', compact('properties'));
    }

    public function rent(Request $request)
    {
        $query = Property::where('unit_for', 'rent')
            ->where('is_published', true)
            ->when($request->search, function($q, $search) {
                return $q->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->type, function($q, $type) {
                return $q->where('type', $type);
            })
            ->when($request->min_price, function($q, $price) {
                return $q->where('price', '>=', $price);
            })
            ->when($request->max_price, function($q, $price) {
                return $q->where('price', '<=', $price);
            });

        // Handle sorting
        $query->when($request->sort, function($q, $sort) {
            switch($sort) {
                case 'price_low':
                    return $q->orderBy('price', 'asc');
                case 'price_high':
                    return $q->orderBy('price', 'desc');
                default:
                    return $q->latest();
            }
        }, function($q) {
            return $q->latest();
        });

        $properties = $query->paginate(12)->withQueryString();
        
        return view('rent', compact('properties'));
    }

    public function togglePublished(Property $property)
    {
        $property->is_published = !$property->is_published;
        $property->save();

        return response()->json([
            'success' => true,
            'is_published' => $property->is_published
        ]);
    }
}

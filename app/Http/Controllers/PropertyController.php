<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Property::with(['media', 'handler']) // Eager load relationships
            ->when($request->search, function($q, $search) {
                return $q->where(function($query) use ($search) {
                    $query->where('property_name', 'like', "%{$search}%")
                        ->orWhere('property_number', 'like', "%{$search}%")
                        ->orWhere('compound_name', 'like', "%{$search}%");
                });
            })
            ->when($request->type, function($q, $type) {
                return $q->where('type', $type);
            })
            ->when($request->status, function($q, $status) {
                return $q->where('status', $status);
            })
            ->when($request->price_range, function($q, $range) {
                $ranges = explode('-', $range);
                if (count($ranges) == 2) {
                    return $q->whereBetween('total_price', [$ranges[0], $ranges[1]]);
                }
                return $q;
            });

        // Calculate stats from the base query
        $stats = [
            'total' => $query->count(),
            'available' => $query->clone()->where('status', 'available')->count(),
            'sold' => $query->clone()->where('status', 'sold')->count(),
            'rented' => $query->clone()->where('status', 'rented')->count()
        ];

        // Add these variables for filters
        $propertyTypes = [
            'apartment' => __('Apartment'),
            'villa' => __('Villa'),
            'duplex' => __('Duplex'),
            'penthouse' => __('Penthouse'),
            'studio' => __('Studio'),
            'office' => __('Office'),
            'retail' => __('Retail'),
            'land' => __('Land')
        ];

        $statuses = [
            'available' => __('Available'),
            'sold' => __('Sold'),
            'rented' => __('Rented'),
            'reserved' => __('Reserved')
        ];

        // Get paginated results
        $properties = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // Debug properties
        \Log::info('Properties count: ' . $properties->count());
        \Log::info('First property: ', $properties->first() ? $properties->first()->toArray() : ['no properties']);

        return view('properties.index', compact(
            'properties',
            'stats',
            'propertyTypes',
            'statuses'
        ));
    }

    private function handleImageUrl($imagePath)
    {
        // إذا كان المسار URL كامل
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }
        
        // إذا كان المسار يبدأ بـ storage/
        if (str_starts_with($imagePath, 'storage/')) {
            return asset($imagePath);
        }
        
        // إذا كان المسار في مجلد التخزين
        return asset('storage/' . $imagePath);
    }

    private function getFormData()
    {
        return [
            'features' => [
                'balcony', 'built-in kitchen', 'private garden', 'security',
                'central ac', 'parking', 'elevator', 'maids room', 'pool', 'gym'
            ],
            'amenities' => [
                'swimming pool', 'gym', 'sauna', 'kids area', 'parking',
                'security', 'mosque', 'shopping area', 'school', 'hospital',
                'restaurant', 'cafe'
            ],
            'propertyTypes' => [
                'apartment', 'villa', 'duplex', 'penthouse', 'studio',
                'office', 'retail', 'land'
            ],
            'categories' => ['residential', 'commercial', 'administrative'],
            'statuses' => ['available', 'sold', 'rented', 'reserved'],
            'currencies' => ['EGP', 'USD', 'EUR'],
            'contactStatuses' => ['contacted', 'pending', 'no_answer'],
            'offerTypes' => ['owner', 'agent', 'company']
        ];
    }

    public function show(Property $property)
    {
        $property->load(['media', 'handler', 'project']);
        $formData = $this->getFormData();
        
        $property->media->each(function ($media) {
            $media->file_path = $this->handleImageUrl($media->file_path);
        });
        
        return view('properties.show', array_merge(
            compact('property'),
            $formData
        ));
    }

    public function create()
    {
        try {
            $users = User::where('company_id', auth()->user()->company_id)->get();
            $projects = Project::where('company_id', auth()->user()->company_id)->get();
            $formData = $this->getFormData();

            return view('properties.create', array_merge(
                compact('users', 'projects'),
                $formData
            ));
        } catch (\Exception $e) {
            // If projects table doesn't exist, continue without projects
            $users = User::where('company_id', auth()->user()->company_id)->get();
            $formData = $this->getFormData();

            return view('properties.create', array_merge(
                compact('users'),
                ['projects' => collect()],
                $formData
            ));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_name' => 'required|string|max:255',
            'unit_for' => 'required|in:sale,rent',
            'type' => 'required|string',
            'total_area' => 'required|numeric',
            'total_price' => 'required|numeric',
            'team_id' => 'nullable|exists:teams,id'
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        $validated['created_by'] = auth()->id();

        $property = Property::create($validated);

        // Handle media uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store("companies/{$property->company_id}/properties", 'public');
                $property->media()->create([
                    'type' => 'image',
                    'file_path' => $path,
                    'is_featured' => $property->media()->count() === 0
                ]);
            }
        }

        return redirect()->route('properties.show', $property)
            ->with('success', __('Property created successfully'));
    }

    public function edit(Property $property)
    {
        $property->load(['media', 'handler']); // Load relationships
        $users = User::where('company_id', auth()->user()->company_id)->get();
        $projects = Project::where('company_id', auth()->user()->company_id)->get();
        $formData = $this->getFormData();

        return view('properties.edit', array_merge(
            compact('property', 'users', 'projects'),
            $formData
        ));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'property_name' => 'required|string|max:255',
            'unit_for' => 'required|in:sale,rent',
            'type' => 'required|string',
            'total_area' => 'required|numeric',
            'total_price' => 'required|numeric',
            'rooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'features' => 'nullable|array',
            'amenities' => 'nullable|array',
            'status' => 'required|string',
            'currency' => 'required|string|size:3',
            // ...other validation rules...
        ]);

        $property->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store("companies/{$property->company_id}/properties", 'public');
                $property->media()->create([
                    'type' => 'image',
                    'file_path' => $path,
                    'is_featured' => false
                ]);
            }
        }

        if ($request->action === 'save_and_continue') {
            return back()->with('success', __('Property updated successfully.'));
        }

        return redirect()->route('properties.show', $property)
            ->with('success', __('Property updated successfully.'));
    }

    public function destroy(Property $property)
    {
        $property->delete();
        return redirect()->route('properties.index')->with('success', 'Property deleted successfully');
    }
}

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
        $query = Property::with(['handler', 'project', 'media'])
            ->when($request->search, function($q) use($request) {
                $q->where('property_name', 'like', "%{$request->search}%")
                  ->orWhere('property_number', 'like', "%{$request->search}%");
            })
            ->when($request->type, function($q) use($request) {
                $q->where('type', $request->type);
            })
            ->when($request->status, function($q) use($request) {
                $q->where('status', $request->status);
            })
            ->when($request->unit_for, function($q) use($request) {
                $q->where('unit_for', $request->unit_for);
            });

        $properties = $query->latest()->paginate(10);

        $stats = [
            'total' => Property::count(),
            'available' => Property::where('status', 'available')->count(),
            'featured' => Property::where('is_featured', true)->count()
        ];

        return view('properties.index', compact('properties', 'stats'));
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
        $users = User::all();
        $projects = Project::all();
        $formData = $this->getFormData();

        return view('properties.create', array_merge(compact('users', 'projects'), $formData));
    }

    public function store(Request $request)
    {
        // Add validation and store logic
    }

    public function edit(Property $property)
    {
        $users = User::all();
        $projects = Project::all();
        $formData = $this->getFormData();

        return view('properties.edit', array_merge(
            compact('property', 'users', 'projects'),
            $formData
        ));
    }

    public function update(Request $request, Property $property)
    {
        // Add validation and update logic
    }

    public function destroy(Property $property)
    {
        $property->delete();
        return redirect()->route('properties.index')->with('success', 'Property deleted successfully');
    }
}

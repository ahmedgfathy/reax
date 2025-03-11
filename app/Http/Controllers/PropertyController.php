<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index()
    {
        $propertyTypes = [
            'apartment' => __('Apartment'),
            'villa' => __('Villa'),
            'office' => __('Office'),
            'retail' => __('Retail'),
            'land' => __('Land'),
            'building' => __('Building'),
            'warehouse' => __('Warehouse'),
            'other' => __('Other')
        ];

        $properties = Property::paginate(10);
        
        return view('properties.index', compact('properties', 'propertyTypes'));
    }

    public function show(Property $property)
    {
        return view('properties.show', compact('property'));
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(Request $request)
    {
        // Add validation and store logic
    }

    public function edit(Property $property)
    {
        return view('properties.edit', compact('property'));
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

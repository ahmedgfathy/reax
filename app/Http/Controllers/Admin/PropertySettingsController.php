<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertySettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get property statistics
        $query = Property::query();
        if (!$user->isSuperAdmin()) {
            $query->where('company_id', $user->company_id);
        }

        $stats = [
            'total' => $query->count(),
            'published' => $query->where('is_published', true)->count(),
            'for_sale' => $query->where('unit_for', 'sale')->count(),
            'for_rent' => $query->where('unit_for', 'rent')->count(),
        ];

        // Get property types for settings
        $propertyTypes = Property::select('type')
            ->distinct()
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->where('type', '!=', 'no selected value')
            ->pluck('type')
            ->filter()
            ->sort()
            ->values();

        return view('administration.property.settings', compact('stats', 'propertyTypes'));
    }
}

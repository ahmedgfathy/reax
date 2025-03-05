<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Show the application home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get featured properties for the home page - with error handling
        try {
            // First check if the is_featured column exists
            if (DB::getSchemaBuilder()->hasColumn('properties', 'is_featured')) {
                $featuredProperties = Property::where('is_featured', true)
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
            } else {
                // Fallback if column doesn't exist
                $featuredProperties = Property::orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
            }
        } catch (\Exception $e) {
            // If there's any error, show recent properties
            $featuredProperties = Property::orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        }
            
        return view('home', compact('featuredProperties'));
    }

    /**
     * Display all featured properties.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function featuredProperties(Request $request)
    {
        try {
            // Get featured properties with pagination
            $featuredProperties = Property::where('is_featured', true)
                ->orderBy('created_at', 'desc')
                ->paginate(9);  // 9 properties per page (3x3 grid)
        } catch (\Exception $e) {
            // If there's any error, return empty collection
            $featuredProperties = collect([]);
        }
        
        return view('featured-properties', compact('featuredProperties'));
    }
}

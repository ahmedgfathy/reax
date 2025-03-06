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
            $featuredProperties = Property::where('is_published', true)
                ->where('is_featured', true)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        } catch (\Exception $e) {
            // If there's any error, return empty collection
            $featuredProperties = collect([]);
        }
            
        return view('home', compact('featuredProperties'));
    }

    /**
     * Display all featured properties.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function featuredProperties()
    {
        $featuredProperties = Property::where('is_featured', true)
            ->where('is_published', true)
            ->latest()
            ->paginate(12);
            
        return view('featured-properties', compact('featuredProperties'));
    }
}

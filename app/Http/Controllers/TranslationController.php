<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TranslationController extends Controller
{
    public function getTranslations($locale)
    {
        // Ensure the locale is valid
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }
        
        // Path to the JSON language file
        $path = resource_path("lang/{$locale}.json");
        
        // Check if file exists
        if (!File::exists($path)) {
            return response()->json([]);
        }
        
        // Read and return the translations
        $translations = json_decode(file_get_contents($path), true);
        return response()->json($translations);
    }
}

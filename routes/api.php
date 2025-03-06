<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TranslationController extends Controller
{
    public function getTranslations($locale)
    {
        $path = resource_path("lang/{$locale}.json");

        if (!File::exists($path)) {
            return response()->json(['error' => 'Translations not found.'], 404);
        }

        $translations = json_decode(File::get($path), true);

        return response()->json($translations);
    }
}
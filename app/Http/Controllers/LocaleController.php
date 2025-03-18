<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function switchLocale(Request $request)
    {
        $locale = $request->input('locale');
        
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }
        
        Session::put('locale', $locale);
        App::setLocale($locale);
        
        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json(['locale' => $locale]);
        }
        
        return redirect()->back();
    }
}

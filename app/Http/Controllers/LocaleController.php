<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class LocaleController extends Controller
{
    public function switchLocale(Request $request)
    {
        $locale = $request->input('locale');
        
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }
        
        // Store in session
        Session::put('locale', $locale);
        
        // Set locale for the current request
        App::setLocale($locale);
        
        // Create cookies with proper settings for JavaScript access
        $cookie = Cookie::make('locale', $locale, 60 * 24 * 30, null, null, false, false);
        $rtlCookie = Cookie::make('is_rtl', $locale === 'ar' ? '1' : '0', 60 * 24 * 30, null, null, false, false);
        
        return redirect()->back()->withCookie($cookie)->withCookie($rtlCookie);
    }
}

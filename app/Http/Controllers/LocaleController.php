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
        $locale = $request->locale;
        
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }
        
        // Store in session
        Session::put('locale', $locale);
        App::setLocale($locale);
        
        // Set cookie with proper options for JavaScript access
        $cookie = Cookie::make('locale', $locale, 60 * 24 * 30);
        $rtlCookie = Cookie::make('is_rtl', $locale === 'ar' ? '1' : '0', 60 * 24 * 30);
        
        return redirect()->back()->withCookie($cookie)->withCookie($rtlCookie);
    }
}

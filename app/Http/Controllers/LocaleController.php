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
        
        // Set session and application locale
        Session::put('locale', $locale);
        App::setLocale($locale);
        
        // Create response with cookie
        $response = redirect()->back()
            ->with([
                'locale_changed' => true,
                'locale' => $locale,
                'message' => 'Language changed to ' . ($locale === 'ar' ? 'Arabic' : 'English')
            ]);
            
        // Add the locale cookie to the response
        $response->withCookie(Cookie::forever('locale', $locale));
        
        return $response;
    }
}

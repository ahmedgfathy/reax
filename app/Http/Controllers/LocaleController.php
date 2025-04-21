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
        
        // Create a permanent cookie
        Cookie::queue(Cookie::forever('locale', $locale));
        
        if ($request->ajax()) {
            return response()->json([
                'locale' => $locale,
                'success' => true,
                'redirect' => url()->previous(),
                'direction' => $locale === 'ar' ? 'rtl' : 'ltr',
                'isRtl' => $locale === 'ar'
            ])->withCookie(Cookie::forever('locale', $locale));
        }
        
        return redirect()->back()
            ->withCookie(Cookie::forever('locale', $locale))
            ->with([
                'locale_changed' => true,
                'locale' => $locale
            ]);
    }
}

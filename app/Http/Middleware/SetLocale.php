<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check for locale in session first
        if (Session::has('locale')) {
            $locale = Session::get('locale');
        } 
        // Check for locale in cookie as fallback
        else if ($request->cookie('locale')) {
            $locale = $request->cookie('locale');
            Session::put('locale', $locale);
        } 
        // Default to config
        else {
            $locale = config('app.locale', 'en');
        }
        
        // Ensure it's a valid locale
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }
        
        // Set the application locale
        App::setLocale($locale);
        
        return $next($request);
    }
}

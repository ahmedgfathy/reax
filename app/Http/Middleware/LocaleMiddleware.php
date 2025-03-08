<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Get locale from session or default to 'en'
        $locale = session('locale', 'en');
        
        // Set the application locale
        App::setLocale($locale);
        
        return $next($request);
    }
}

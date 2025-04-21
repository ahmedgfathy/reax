<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check session first
        if (Session::has('locale')) {
            $locale = Session::get('locale');
        }
        // Then check browser preference
        else {
            $locale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            // Fallback to default if not supported
            if (!in_array($locale, ['en', 'ar'])) {
                $locale = config('app.locale');
            }
            // Store in session
            Session::put('locale', $locale);
        }

        // Set the application locale
        App::setLocale($locale);
        
        // Share locale data with all views
        View::share('currentLocale', $locale);
        View::share('isRtl', $locale === 'ar');
        
        $response = $next($request);
        
        // If response is HTML, set the lang and dir attributes
        if ($response instanceof Response && 
            strpos($response->headers->get('Content-Type'), 'text/html') !== false) {
            $content = $response->getContent();
            
            // Update HTML tag with lang and dir attributes
            $content = preg_replace(
                '/<html[^>]*>/',
                '<html lang="' . $locale . '" dir="' . ($locale === 'ar' ? 'rtl' : 'ltr') . '">',
                $content
            );
            
            $response->setContent($content);
        }
        
        return $response;
    }
}

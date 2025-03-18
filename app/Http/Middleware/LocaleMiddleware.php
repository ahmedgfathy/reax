<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

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
        }

        App::setLocale($locale);
        return $next($request);
    }
}

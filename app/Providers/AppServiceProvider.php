<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register a custom Vite instance that won't throw exceptions in development
        if (!file_exists(public_path('build/manifest.json'))) {
            $this->app->singleton(Vite::class, function () {
                return new class {
                    public function __invoke($entrypoints)
                    {
                        return '';
                    }
                    
                    public function __call($name, $arguments)
                    {
                        return '';
                    }
                };
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Register app-layout component
        Blade::component('layouts.app', 'app-layout');

        // Share common data with views
        View::composer('*', function ($view) {
            $view->with('currentLocale', App::getLocale());
            $view->with('isRtl', App::getLocale() === 'ar');
        });

        // Set default string length for MySQL < 5.7.7
        Schema::defaultStringLength(191);
        
        // Set locale from cookie first, then session
        $locale = request()->cookie('locale') ?? Session::get('locale');
        if ($locale && in_array($locale, ['en', 'ar'])) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        }
    }
}

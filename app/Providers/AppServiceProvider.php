<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;

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
        // Register app-layout component
        Blade::component('layouts.app', 'app-layout');

        // Share current locale with all views
        View::composer('*', function ($view) {
            $view->with('currentLocale', App::getLocale());
            $view->with('isRtl', App::getLocale() === 'ar');
        });
    }
}

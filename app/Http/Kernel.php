<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            // Move LocaleMiddleware to the top to ensure it runs first
            \App\Http\Middleware\LocaleMiddleware::class,
            \App\Http\Middleware\SetLocale::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\TrackUserSession::class,
        ],

        'api' => [
        ],
    ];
}

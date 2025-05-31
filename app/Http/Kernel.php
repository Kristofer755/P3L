<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        // … kamu bisa sesuaikan group lainnya …
    ];

    /**
     * The application's route middleware.
     */
    protected $routeMiddleware = [
        // middleware Laravel built-in …
        'auth'       => \App\Http\Middleware\Authenticate::class,
        'cek.pembeli'=> \App\Http\Middleware\CekPembeliLogin::class,
    ];
}

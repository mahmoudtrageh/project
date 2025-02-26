<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Modules\Admin\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckAuth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.admin' => AdminMiddleware::class,
            'locale' => SetLocale::class,
            'auth.check' => CheckAuth::class
        ]);
        
        // Then, apply the middleware to the web group
        $middleware->web(append: [
            'locale'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

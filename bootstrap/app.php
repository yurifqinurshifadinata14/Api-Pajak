<?php

use App\Http\Middleware\Admin;
use App\Http\Middleware\Auth;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\Multi;
use App\Http\Middleware\Staff;
 use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'auth' => Auth::class,
            'role' => CheckRole::class,
            'admin' => Admin::class,
            'staff' => Staff::class,
            'multi' => Multi::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

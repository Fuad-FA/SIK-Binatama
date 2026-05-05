<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->alias([
        //     'role'              => \App\Http\Middleware\RoleMiddleware::class,
        //     'check.first.login' => \App\Http\Middleware\CheckFirstLogin::class,
        // ]);
        $middleware->alias([
            'role'              => \App\Http\Middleware\RoleMiddleware::class,
            'check.first.login' => \App\Http\Middleware\CheckFirstLogin::class,
            'patient.auth'      => \App\Http\Middleware\PatientAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
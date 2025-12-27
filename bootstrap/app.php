<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureStudentHasAccess;
use App\Http\Middleware\EnsureLandlordVerified;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'category' => \App\Http\Middleware\CheckCategory::class,
            'student.access'   => \App\Http\Middleware\EnsureStudentHasAccess::class,
            'landlord.verified'=> \App\Http\Middleware\EnsureLandlordVerified::class,
            // Add other middleware aliases here
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
  
    
    
    /*return Application::configure(basePath: dirname(__DIR__))
        // ...
        ->withMiddleware(function (Middleware $middleware) {
            $middleware->alias([
                'auth'             => \App\Http\Middleware\Authenticate::class,
                // your new ones:
                'student.access'   => EnsureStudentHasAccess::class,
                'landlord.verified'=> EnsureLandlordVerified::class,
            ]);
        })
        // ...
        ->create();
    */
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(
    basePath: dirname(__DIR__)
)
->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
->withMiddleware(function (Middleware $middleware): void {

    /*
    |--------------------------------------------------------------------------
    | Middleware Aliases
    |--------------------------------------------------------------------------
    */

    $middleware->alias([

        'role' =>
            \App\Http\Middleware\RoleMiddleware::class,

        'permission' =>
            \App\Http\Middleware\PermissionMiddleware::class,

    ]);


    /*
    |--------------------------------------------------------------------------
    | Activity Logging
    |--------------------------------------------------------------------------
    |
    | Automatically records POST, PUT, PATCH, and DELETE operations
    | performed by authenticated users.
    |
    */

    $middleware->appendToGroup(
        'web',
        \App\Http\Middleware\ActivityLogMiddleware::class
    );

})
->withExceptions(function (Exceptions $exceptions): void {

    //

})
->create();
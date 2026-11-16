<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;

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

    $middleware->appendToGroup(
    'web',
    \App\Http\Middleware\EnsureAccountIsActive::class
);

})
->withExceptions(function (Exceptions $exceptions): void {

    // Keep the throttle limits intact. Only improve the UI for password-change
    // OTP form submissions. All other 429 responses keep Laravel's defaults.
    $exceptions->render(function (ThrottleRequestsException $exception, Request $request) {
        $isSendRequest = $request->is('password/change-otp/send');
        $isVerifyRequest = $request->is('password/change-otp/verify');

        if ((! $isSendRequest && ! $isVerifyRequest) || $request->expectsJson()) {
            return null;
        }

        $retryAfter = (int) ($exception->getHeaders()['Retry-After'] ?? 0);
        $retryMessage = $retryAfter > 0
            ? ' Please try again in about '.max(1, (int) ceil($retryAfter / 60)).' minute(s).'
            : ' Please try again later.';

        $message = ($isSendRequest
            ? 'Too many verification code requests.'
            : 'Too many verification attempts.') . $retryMessage;

        return redirect('/settings#security')->withErrors(
            [$isSendRequest ? 'current_password' : 'otp' => $message],
            $isSendRequest ? 'passwordOtpRequest' : 'passwordOtpVerify'
        );
    });

})
->create();

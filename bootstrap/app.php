<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle CSRF token mismatch specifically
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'CSRF token mismatch. Please refresh the page and try again.',
                    'error' => 'csrf_mismatch',
                    'timestamp' => now()->toDateTimeString(),
                ], 419);
            }

            return response()->view('errors.csrf', [], 419);
        });
    })->create();

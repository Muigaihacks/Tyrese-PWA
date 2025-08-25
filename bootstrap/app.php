<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Log all exceptions for debugging (simplified to prevent recursion)
        $exceptions->report(function (\Throwable $e) {
            // Prevent infinite recursion by checking if this is a logging-related error
            if (str_contains($e->getMessage(), 'Maximum call stack size') || 
                str_contains($e->getMessage(), 'recursion') ||
                str_contains($e->getFile(), 'Log.php')) {
                return; // Skip logging to prevent infinite loop
            }

            Log::error('Application exception occurred', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'timestamp' => now()->toDateTimeString(),
            ]);
        });

        // Log database connection errors specifically (simplified)
        $exceptions->report(function (\Illuminate\Database\QueryException $e) {
            // Prevent infinite recursion
            if (str_contains($e->getMessage(), 'Maximum call stack size') || 
                str_contains($e->getMessage(), 'recursion')) {
                return;
            }

            Log::critical('Database query exception', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'timestamp' => now()->toDateTimeString(),
            ]);
        });
    })->create();

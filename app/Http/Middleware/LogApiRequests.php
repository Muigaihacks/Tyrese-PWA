<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip logging for health check endpoints to prevent infinite recursion
        if ($request->is('api/health') || $request->is('api/status') || $request->is('api/debug-user')) {
            return $next($request);
        }

        $startTime = microtime(true);
        
        // Log incoming request (simplified to prevent recursion)
        Log::info('API Request received', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'timestamp' => now()->toDateTimeString()
        ]);

        // Handle the request
        $response = $next($request);

        // Calculate response time
        $responseTime = microtime(true) - $startTime;

        // Log response (simplified to prevent recursion)
        Log::info('API Response sent', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status_code' => $response->getStatusCode(),
            'response_time_ms' => round($responseTime * 1000, 2),
            'timestamp' => now()->toDateTimeString()
        ]);

        return $response;
    }
}

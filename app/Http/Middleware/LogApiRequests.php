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
        $startTime = microtime(true);
        
        // Log incoming request
        Log::info('API Request received', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'headers' => $request->headers->all(),
            'request_time' => now()->toDateTimeString(),
            'request_id' => uniqid('req_', true)
        ]);

        // Handle the request
        $response = $next($request);

        // Calculate response time
        $responseTime = microtime(true) - $startTime;

        // Log response
        Log::info('API Response sent', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status_code' => $response->getStatusCode(),
            'response_time_ms' => round($responseTime * 1000, 2),
            'response_time' => now()->toDateTimeString(),
            'request_id' => uniqid('req_', true)
        ]);

        return $response;
    }
}

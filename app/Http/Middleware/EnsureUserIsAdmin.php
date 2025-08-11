<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access to login page
        if ($request->routeIs('filament.admin.auth.login') || 
            $request->routeIs('filament.admin.auth.logout') ||
            $request->is('admin/login')) {
            return $next($request);
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('filament.admin.auth.login');
        }

        $user = Auth::user();
        
        // Check if user has admin role
        if (!$user->hasRole('admin')) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}

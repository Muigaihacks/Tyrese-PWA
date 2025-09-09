<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        // Log the request for debugging
        Log::info('Admin middleware check', [
            'url' => $request->fullUrl(),
            'route' => $request->route() ? $request->route()->getName() : 'no-route',
            'authenticated' => Auth::check(),
            'user_id' => Auth::id(),
            'user_role' => Auth::user() ? Auth::user()->role : 'no-user',
            'session_id' => session()->getId(),
            'timestamp' => now()->toDateTimeString()
        ]);

        // Allow access to login page
        if ($request->routeIs('filament.admin.auth.login') || 
            $request->routeIs('filament.admin.auth.logout') ||
            $request->is('admin/login') ||
            $request->is('debug-auth') ||
            $request->is('debug-roles')) {
            Log::info('Admin middleware - Allowing access to login/debug page');
            return $next($request);
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            Log::warning('Admin middleware - User not authenticated, redirecting to login');
            return redirect()->route('filament.admin.auth.login');
        }

        $user = Auth::user();
        
        // Log detailed user information
        Log::info('Admin middleware - User details', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'has_admin_role' => $user->hasRole('admin'),
            'all_roles' => $user->roles->pluck('name')->toArray(),
            'timestamp' => now()->toDateTimeString()
        ]);
        
        // Check if user has admin role
        if (!$user->hasRole('admin')) {
            Log::warning('Admin middleware - User does not have admin role', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'has_admin_role' => $user->hasRole('admin'),
                'all_roles' => $user->roles->pluck('name')->toArray(),
                'timestamp' => now()->toDateTimeString()
            ]);
            abort(403, 'Access denied. Admin privileges required.');
        }

        Log::info('Admin middleware - Access granted');
        return $next($request);
    }
}

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// Debug route to check authentication status
Route::get('/debug-auth', function (Request $request) {
    return response()->json([
        'authenticated' => Auth::check(),
        'user' => Auth::user() ? [
            'id' => Auth::user()->id,
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'role' => Auth::user()->role,
        ] : null,
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
        'csrf_token' => csrf_token(),
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Debug route to check user roles
Route::get('/debug-roles', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Not authenticated'], 401);
    }

    $user = Auth::user();
    return response()->json([
        'user_id' => $user->id,
        'user_email' => $user->email,
        'user_role' => $user->role,
        'has_admin_role' => $user->hasRole('admin'),
        'all_roles' => $user->roles->pluck('name')->toArray(),
        'all_permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Catch-all route for React SPA (excluding /api and /admin routes)
Route::get('/{any}', function () {
    return view('app'); // Make sure resources/views/app.blade.php exists and loads your React app
})->where('any', '^(?!api|admin).*$');

require __DIR__.'/auth.php';

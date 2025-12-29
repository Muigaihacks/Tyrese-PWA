<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        // Log login attempt
        Log::info('Login attempt started', [
            'email' => $credentials['email'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString()
        ]);

        // Check if user exists
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            Log::warning('Login failed - User not found', [
                'email' => $credentials['email'],
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json(['message' => 'Login failed. Please try again.'], 401);
        }

        // Log user found
        Log::info('User found during login', [
            'user_id' => $user->id,
            'email' => $user->email,
            'status' => $user->status ?? 'not_set',
            'email_verified_at' => $user->email_verified_at,
            'created_at' => $user->created_at,
            'timestamp' => now()->toDateTimeString()
        ]);

        // Check password
        if (!Hash::check($credentials['password'], $user->password)) {
            Log::warning('Login failed - Invalid password', [
                'user_id' => $user->id,
                'email' => $user->email,
                'password_provided' => !empty($credentials['password']),
                'password_hash_exists' => !empty($user->password),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json(['message' => 'Login failed. Please try again.'], 401);
        }

        // Check user status (handle both boolean true and integer 1)
        if (isset($user->status) && $user->status !== true && $user->status !== 1) {
            Log::warning('Login failed - User not active', [
                'user_id' => $user->id,
                'email' => $user->email,
                'status' => $user->status,
                'status_type' => gettype($user->status),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json(['message' => 'User is not active'], 403);
        }

        // Actually log the user in (creates session for Sanctum)
        Auth::login($user, $request->boolean('remember'));

        // Log successful login
        Log::info('Login successful', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'timestamp' => now()->toDateTimeString()
        ]);

        // Regenerate session to prevent session fixation
        $request->session()->regenerate();

        // Return user data (matching what React expects)
        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ], 200);
    }
}

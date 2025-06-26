<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Dummy user validation (replace with database query in production)
        $user = ['email' => 'user@example.com', 'password' => Hash::make('password123')];

        if ($credentials['email'] === $user['email'] && Hash::check($credentials['password'], $user['password'])) {
            // In a real app, generate a JWT or session token
            return response()->json(['message' => 'Login successful', 'token' => 'dummy-token'], 200);
        }

        return response()->json(['message' => 'Invalid email or password'], 401);
    }
}

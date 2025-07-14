<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Add this at the top if not present

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            // Only allow active users
            if (isset($user->status) && $user->status != 1) {
                return response()->json(['message' => 'User is not active'], 403);
            }

            // In a real app, generate a JWT or session token
            return response()->json(['message' => 'Login successful', 'token' => 'dummy-token'], 200);
        }

        return response()->json(['message' => 'Invalid email or password'], 401);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PasswordChangeLog;

class PasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8',
                'confirm_password' => 'required|same:new_password',
            ]);

            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['message' => 'User not authenticated.'], 401);
            }

            // Simple check - only block admin users
            if ($user->role === 'admin') {
                return response()->json(['message' => 'Admin users cannot change passwords through this interface.'], 403);
            }

            // Check current password
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Current password is incorrect.'], 400);
            }

            // Update password
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            // Log the password change
            PasswordChangeLog::create([
                'user_id' => $user->id,
                'changed_at' => now(),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Password changed successfully!',
                'remaining_changes' => $user->getRemainingPasswordChanges()
            ]);
        } catch (\Exception $e) {
            \Log::error('Password change error: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while changing password: ' . $e->getMessage()], 500);
        }
    }

    public function getPasswordChangeStatus(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        return response()->json([
            'can_change_password' => $user->role !== 'admin',
            'remaining_changes' => $user->getRemainingPasswordChanges(),
            'is_admin' => $user->role === 'admin',
            'monthly_limit' => 3
        ]);
    }
}

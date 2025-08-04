<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\LeasedUnitController;
use App\Http\Controllers\BatteryController;
use App\Http\Controllers\CasualLabourerController;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Password change route
    Route::post('/change-password', function (Request $request) {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 400);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json(['message' => 'Password changed successfully!']);
    });

    Route::get('/inventories', [InventoryController::class, 'index']);
    Route::get('/locations/dropdown', [LocationController::class, 'dropdown']);
    Route::get('/visits/dropdown', [VisitController::class, 'dropdown']);
    Route::post('/inventory/checkout', [InventoryController::class, 'checkout']);
    Route::post('/inventory/return', [InventoryController::class, 'return']);
    Route::get('/leased-units', [LeasedUnitController::class, 'index']);
    Route::post('/visits/schedule', [VisitController::class, 'schedule']);
    Route::post('/storage', [\App\Http\Controllers\StorageController::class, 'store']);
    Route::post('/insurances', [\App\Http\Controllers\InsuranceController::class, 'store']);
    
    // Battery Management Routes
    Route::get('/batteries', [BatteryController::class, 'index']);
    Route::post('/batteries/swap', [BatteryController::class, 'swap']);
    
    // Casual Labourer Routes
    Route::get('/casual-labourer/profile', [CasualLabourerController::class, 'getProfile']);
    Route::post('/casual-labourer/profile', [CasualLabourerController::class, 'updateProfile']);
    Route::post('/casual-labourer/time-in', [CasualLabourerController::class, 'timeIn']);
    Route::post('/casual-labourer/time-out', [CasualLabourerController::class, 'timeOut']);
    Route::get('/casual-labourer/attendance-history', [CasualLabourerController::class, 'getAttendanceHistory']);
});

Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? response()->json(['message' => __($status)])
        : response()->json(['message' => __($status)], 400);
})->middleware('api');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? response()->json(['message' => 'Password reset successful!'])
        : response()->json(['message' => __($status)], 400);
})->middleware('api');

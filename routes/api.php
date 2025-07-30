<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\LeasedUnitController;
use App\Http\Controllers\BatteryController;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
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
});

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
});

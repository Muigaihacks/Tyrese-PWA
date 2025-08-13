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

    // Password management routes
    Route::post('/change-password', [\App\Http\Controllers\PasswordController::class, 'changePassword']);
    Route::get('/password-change-status', [\App\Http\Controllers\PasswordController::class, 'getPasswordChangeStatus']);

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
    
    // Crate Tracker Routes
    Route::get('/crate-tracker/hubs', [\App\Http\Controllers\CrateTrackerController::class, 'getHubs']);
    Route::post('/crate-tracker/movement', [\App\Http\Controllers\CrateTrackerController::class, 'createMovement']);
    Route::get('/crate-tracker/movements', [\App\Http\Controllers\CrateTrackerController::class, 'getMovements']);
    Route::get('/crate-tracker/cold-storage-units', [\App\Http\Controllers\CrateTrackerController::class, 'getColdStorageUnits']);
    Route::post('/crate-tracker/cold-storage-unit', [\App\Http\Controllers\CrateTrackerController::class, 'updateColdStorageUnit']);
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

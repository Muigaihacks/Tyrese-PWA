<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\BatteryController;
use App\Http\Controllers\LeasedUnitController;
use App\Http\Controllers\CrateTrackerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Named route for password reset (fallback/placeholder for Laravel)
Route::get('/reset-password/{token}', function ($token) {
    $email = request()->input('email');
    return redirect(config('app.frontend_url') . "/reset-password?token={$token}&email={$email}");
})->name('password.reset');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Inventory routes
    Route::get('/inventories', [InventoryController::class, 'index']);
    Route::post('/inventory/checkout', [InventoryController::class, 'checkout']);
    Route::post('/inventory/return', [InventoryController::class, 'return']);
    Route::get('/locations/dropdown', [InventoryController::class, 'locationsDropdown']);
    
    // Visit routes
    Route::get('/visits/dropdown', [VisitController::class, 'dropdown']);
    
    // Storage routes
    Route::post('/storage', [StorageController::class, 'store']);
    
    // Battery routes
    Route::get('/batteries', [BatteryController::class, 'index']);
    Route::post('/batteries/swap', [BatteryController::class, 'swap']);
    
    // Leased Units (Cold Storage Units) routes
    Route::get('/leased-units', [LeasedUnitController::class, 'index']);
    
    // Crate Tracker routes
    Route::get('/crate-tracker/hubs', [CrateTrackerController::class, 'getHubs']);
    Route::get('/crate-tracker/movements', [CrateTrackerController::class, 'getMovements']);
    Route::post('/crate-tracker/movement', [CrateTrackerController::class, 'createMovement']);
});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\LeasedUnitController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\InsuranceController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
Route::post('/reset-password', [NewPasswordController::class, 'store']);

Route::middleware(['auth:sanctum'])->group(function () {
    // --- Inventory ---
    Route::get('inventories', [InventoryController::class, 'index'])->middleware('permission:inventory.view');
    Route::post('inventories', [InventoryController::class, 'store'])->middleware('permission:inventory.create');
    Route::get('inventories/{inventory}', [InventoryController::class, 'show'])->middleware('permission:inventory.view');
    Route::put('inventories/{inventory}', [InventoryController::class, 'update'])->middleware('permission:inventory.update');
    Route::delete('inventories/{inventory}', [InventoryController::class, 'destroy'])->middleware('permission:inventory.delete');
    // Custom actions
    Route::post('inventories/checkout', [InventoryController::class, 'checkout'])->middleware('permission:inventory.checkout');
    Route::post('inventories/return', [InventoryController::class, 'return'])->middleware('permission:inventory.return');

    // --- Storage ---
    Route::post('storage', [StorageController::class, 'store'])->middleware('permission:storage.create');
    Route::get('storage', [StorageController::class, 'index'])->middleware('permission:storage.view');

    // --- Leased Units (Map) ---
    Route::get('leased-units', [LeasedUnitController::class, 'index'])->middleware('permission:map.view');

    // --- Visits ---
    Route::get('visits', [VisitController::class, 'index'])->middleware('permission:visit.view');
    Route::post('visits/schedule', [VisitController::class, 'schedule'])->middleware('permission:visit.schedule');

    // --- Insurance ---
    Route::post('insurance', [InsuranceController::class, 'store'])->middleware('permission:insurance.create');
    Route::get('insurance', [InsuranceController::class, 'index'])->middleware('permission:insurance.view');
});
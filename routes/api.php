<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\LeasedUnitController;

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
});

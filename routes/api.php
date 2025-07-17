<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\VisitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Add these routes:
    Route::get('/inventories', [InventoryController::class, 'index']);
    Route::get('/locations/dropdown', [LocationController::class, 'dropdown']);
    Route::get('/visits/dropdown', [VisitController::class, 'dropdown']);
});

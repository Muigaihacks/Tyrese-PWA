<?php

use Illuminate\Support\Facades\Route;

// Catch-all route for React SPA (excluding /api routes)
Route::get('/{any}', function () {
    return view('app'); // Make sure resources/views/app.blade.php exists and loads your React app
})->where('any', '^(?!api).*$');

require __DIR__.'/auth.php';

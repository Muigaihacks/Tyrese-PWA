<?php

use Illuminate\Support\Facades\Route;

// Password reset redirect for email links
Route::get('/reset-password/{token}', function ($token) {
    $email = request('email');
    return redirect("/reset-password?token=$token&email=$email");
})->name('password.reset');

// (Optional) Restore auth routes if you had them
// Auth::routes();
// require __DIR__.'/auth.php';

// Catch-all route for React SPA
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

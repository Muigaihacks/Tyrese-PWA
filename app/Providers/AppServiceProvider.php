<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Simplified database connection logging to prevent recursion
        try {
            DB::connection()->getPdo();
            Log::info('Database connection successful', [
                'database' => config('database.connections.' . config('database.default') . '.database'),
                'host' => config('database.connections.' . config('database.default') . '.host'),
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (Exception $e) {
            Log::error('Database connection failed', [
                'error' => $e->getMessage(),
                'database' => config('database.connections.' . config('database.default') . '.database'),
                'host' => config('database.connections.' . config('database.default') . '.host'),
                'timestamp' => now()->toDateTimeString()
            ]);
        }

        // Simplified application startup logging
        Log::info('Sokofresh application started', [
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'timestamp' => now()->toDateTimeString()
        ]);

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}

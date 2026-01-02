<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Auth\Notifications\ResetPassword;

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
        // Force HTTPS URLs in production (Railway uses HTTPS)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        
        // Super admin should never lose visibility/access to Filament modules due to missing permissions.
        // This also fixes cases where policies exist but permissions have not been generated/seeded yet.
        Gate::before(function ($user) {
            return ($user instanceof \App\Models\User && $user->hasRole('super_admin')) ? true : null;
        });

        // Define API rate limiter
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Customize the Reset Password URL to point to the Next.js Frontend
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/reset-password?token={$token}&email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}

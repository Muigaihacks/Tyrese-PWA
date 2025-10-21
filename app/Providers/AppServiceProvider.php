<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Override the Number class to prevent intl requirement
        $this->app->bind(\Illuminate\Support\Number::class, function ($app) {
            return new class {
                public static function format($number, $precision = 0, $maxPrecision = null, $locale = null) {
                    return number_format($number, $precision);
                }
                
                public static function __callStatic($method, $parameters) {
                    // Return the first parameter as-is for any other Number methods
                    return $parameters[0] ?? null;
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

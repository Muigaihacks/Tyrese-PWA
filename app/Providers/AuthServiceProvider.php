<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Insurance::class   => \App\Policies\InsurancePolicy::class,
        \App\Models\LeasedUnit::class  => \App\Policies\LeasedUnitPolicy::class,
        \Spatie\Permission\Models\Role::class => \App\Policies\RolePolicy::class,
        \App\Models\Storage::class     => \App\Policies\StoragePolicy::class,
        \App\Models\User::class        => \App\Policies\UserPolicy::class, // Re-enabled
        \App\Models\Visit::class       => \App\Policies\VisitPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\FontProviders\LocalFontProvider;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Pages\CustomLogin::class)
            // Avoid render-blocking external font loads (e.g. fonts.bunny.net).
            // This prevents Safari from sitting on a blank page if that request is blocked/hangs.
            ->font('system-ui', provider: LocalFontProvider::class)
            ->brandName('Demo System')
            // ->brandLogo(asset('images/logo.jpg')) // Hidden for demo
            ->colors([
                'primary' => Color::Hex('#F59E42'), // Orange
                'sidebar' => Color::Hex('#1E40AF'), // Blue
                'topbar' => Color::Hex('#F3F4F6'),  // Light Gray
                'accent' => Color::Hex('#F59E42'),  // Orange (if used)
                // Add more as needed
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class, // Removed to hide Filament version and links
                \App\Filament\Widgets\AdminDashboardStats::class,
                \App\Filament\Widgets\UpcomingVisitsWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('web')
            ->canAccess(function ($user) {
                // Allow super_admin and admin roles to access the panel
                return $user->hasRole(['super_admin', 'admin']);
            });
    }
}

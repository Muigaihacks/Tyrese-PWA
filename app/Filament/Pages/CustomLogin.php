<?php

namespace App\Filament\Pages;

use Filament\Pages\Auth\Login as BaseLogin;

class CustomLogin extends BaseLogin
{
    public function getHeading(): string
    {
        return 'Admin Login';
    }

    public function getSubheading(): ?string
    {
        return 'Demo Credentials: admin@demo.com / demo123';
    }
}


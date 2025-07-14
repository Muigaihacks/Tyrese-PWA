<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Password;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public static function canCreateAnother(): bool
    {
        return false;
    }

    protected function afterCreate(): void
    {
        $user = $this->record;

        // Generate a password reset token
        $token = Password::broker()->createToken($user);

        // Send the reset notification
        $user->notify(new ResetPassword($token));
    }
}

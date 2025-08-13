<?php

namespace App\Filament\Resources\CrateMovementResource\Pages;

use App\Filament\Resources\CrateMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCrateMovement extends CreateRecord
{
    protected static string $resource = CrateMovementResource::class;

    public static function canCreateAnother(): bool
    {
        return false;
    }
}

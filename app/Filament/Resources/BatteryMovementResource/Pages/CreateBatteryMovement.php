<?php

namespace App\Filament\Resources\BatteryMovementResource\Pages;

use App\Filament\Resources\BatteryMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBatteryMovement extends CreateRecord
{
    protected static string $resource = BatteryMovementResource::class;

    public static function canCreateAnother(): bool
    {
        return false;
    }
}

<?php

namespace App\Filament\Resources\BatteryMovementResource\Pages;

use App\Filament\Resources\BatteryMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBatteryMovement extends EditRecord
{
    protected static string $resource = BatteryMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

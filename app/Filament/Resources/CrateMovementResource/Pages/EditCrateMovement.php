<?php

namespace App\Filament\Resources\CrateMovementResource\Pages;

use App\Filament\Resources\CrateMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCrateMovement extends EditRecord
{
    protected static string $resource = CrateMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

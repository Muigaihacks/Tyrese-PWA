<?php

namespace App\Filament\Resources\LeasedUnitResource\Pages;

use App\Filament\Resources\LeasedUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeasedUnit extends EditRecord
{
    protected static string $resource = LeasedUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\CrateMovementResource\Pages;

use App\Filament\Resources\CrateMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCrateMovements extends ListRecords
{
    protected static string $resource = CrateMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

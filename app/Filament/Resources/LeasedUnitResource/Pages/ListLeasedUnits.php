<?php

namespace App\Filament\Resources\LeasedUnitResource\Pages;

use App\Filament\Resources\LeasedUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeasedUnits extends ListRecords
{
    protected static string $resource = LeasedUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

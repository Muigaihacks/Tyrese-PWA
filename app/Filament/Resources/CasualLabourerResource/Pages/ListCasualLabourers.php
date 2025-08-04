<?php

namespace App\Filament\Resources\CasualLabourerResource\Pages;

use App\Filament\Resources\CasualLabourerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCasualLabourers extends ListRecords
{
    protected static string $resource = CasualLabourerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

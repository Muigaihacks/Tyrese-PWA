<?php

namespace App\Filament\Resources\CasualLabourerResource\Pages;

use App\Filament\Resources\CasualLabourerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCasualLabourer extends EditRecord
{
    protected static string $resource = CasualLabourerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

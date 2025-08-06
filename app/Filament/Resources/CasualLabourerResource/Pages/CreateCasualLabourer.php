<?php

namespace App\Filament\Resources\CasualLabourerResource\Pages;

use App\Filament\Resources\CasualLabourerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCasualLabourer extends CreateRecord
{
    protected static string $resource = CasualLabourerResource::class;

    public static function canCreateAnother(): bool
    {
        return false;
    }
}

<?php

namespace App\Filament\Resources\PasienResource\Pages;

use App\Filament\Resources\PasienResource;
use Filament\Resources\Pages\EditRecord;

class EditPasien extends EditRecord
{
    protected static string $resource = PasienResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

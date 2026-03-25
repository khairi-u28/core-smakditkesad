<?php

namespace App\Filament\Resources\StrukResource\Pages;

use App\Filament\Resources\StrukResource;
use Filament\Resources\Pages\EditRecord;

class EditStruk extends EditRecord
{
    protected static string $resource = StrukResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

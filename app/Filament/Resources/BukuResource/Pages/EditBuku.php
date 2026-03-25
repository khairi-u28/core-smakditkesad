<?php

namespace App\Filament\Resources\BukuResource\Pages;

use App\Filament\Resources\BukuResource;
use Filament\Resources\Pages\EditRecord;

class EditBuku extends EditRecord
{
    protected static string $resource = BukuResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

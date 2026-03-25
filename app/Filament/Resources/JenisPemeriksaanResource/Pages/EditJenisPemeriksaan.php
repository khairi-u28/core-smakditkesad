<?php

namespace App\Filament\Resources\JenisPemeriksaanResource\Pages;

use App\Filament\Resources\JenisPemeriksaanResource;
use Filament\Resources\Pages\EditRecord;

class EditJenisPemeriksaan extends EditRecord
{
    protected static string $resource = JenisPemeriksaanResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

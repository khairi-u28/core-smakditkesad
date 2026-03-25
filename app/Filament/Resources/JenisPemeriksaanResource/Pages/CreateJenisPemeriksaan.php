<?php

namespace App\Filament\Resources\JenisPemeriksaanResource\Pages;

use App\Filament\Resources\JenisPemeriksaanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJenisPemeriksaan extends CreateRecord
{
    protected static string $resource = JenisPemeriksaanResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

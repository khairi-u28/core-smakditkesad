<?php

namespace App\Filament\Resources\JenisPemeriksaanResource\Pages;

use App\Filament\Resources\JenisPemeriksaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisPemeriksaans extends ListRecords
{
    protected static string $resource = JenisPemeriksaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

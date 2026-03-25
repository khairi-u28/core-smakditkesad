<?php

namespace App\Filament\Resources\StrukResource\Pages;

use App\Filament\Resources\StrukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStruks extends ListRecords
{
    protected static string $resource = StrukResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

<?php

namespace App\Filament\Resources\LulusanResource\Pages;

use App\Filament\Resources\LulusanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLusans extends ListRecords
{
    protected static string $resource = LulusanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

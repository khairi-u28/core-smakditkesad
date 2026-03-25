<?php

namespace App\Filament\Resources\LulusanResource\Pages;

use App\Filament\Resources\LulusanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLulusan extends CreateRecord
{
    protected static string $resource = LulusanResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

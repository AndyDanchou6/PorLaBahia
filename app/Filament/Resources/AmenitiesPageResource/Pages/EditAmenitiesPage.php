<?php

namespace App\Filament\Resources\AmenitiesPageResource\Pages;

use App\Filament\Resources\AmenitiesPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAmenitiesPage extends EditRecord
{
    protected static string $resource = AmenitiesPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

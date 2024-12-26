<?php

namespace App\Filament\Resources\AmenitiesPageResource\Pages;

use App\Filament\Resources\AmenitiesPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAmenitiesPages extends ListRecords
{
    protected static string $resource = AmenitiesPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

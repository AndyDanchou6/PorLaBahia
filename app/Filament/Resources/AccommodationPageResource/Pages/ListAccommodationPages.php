<?php

namespace App\Filament\Resources\AccommodationPageResource\Pages;

use App\Filament\Resources\AccommodationPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccommodationPages extends ListRecords
{
    protected static string $resource = AccommodationPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

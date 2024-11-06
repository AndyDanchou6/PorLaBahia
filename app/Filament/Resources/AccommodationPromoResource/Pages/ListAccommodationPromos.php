<?php

namespace App\Filament\Resources\AccommodationPromoResource\Pages;

use App\Filament\Resources\AccommodationPromoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccommodationPromos extends ListRecords
{
    protected static string $resource = AccommodationPromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

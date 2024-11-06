<?php

namespace App\Filament\Resources\AccommodationPromoResource\Pages;

use App\Filament\Resources\AccommodationPromoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccommodationPromo extends EditRecord
{
    protected static string $resource = AccommodationPromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

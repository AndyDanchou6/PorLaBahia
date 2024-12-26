<?php

namespace App\Filament\Resources\AccommodationPageResource\Pages;

use App\Filament\Resources\AccommodationPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccommodationPage extends EditRecord
{
    protected static string $resource = AccommodationPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

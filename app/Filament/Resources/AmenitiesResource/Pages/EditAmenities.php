<?php

namespace App\Filament\Resources\AmenitiesResource\Pages;

use App\Filament\Resources\AmenitiesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAmenities extends EditRecord
{
    protected static string $resource = AmenitiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

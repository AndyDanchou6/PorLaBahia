<?php

namespace App\Filament\Resources\AmenitiesResource\Pages;

use App\Filament\Resources\AmenitiesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAmenities extends ViewRecord
{
    protected static string $resource = AmenitiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

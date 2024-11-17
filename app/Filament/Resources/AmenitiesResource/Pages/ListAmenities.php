<?php

namespace App\Filament\Resources\AmenitiesResource\Pages;

use App\Filament\Resources\AmenitiesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Amenities;


class ListAmenities extends ListRecords
{
    protected static string $resource = AmenitiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

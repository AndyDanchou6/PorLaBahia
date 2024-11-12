<?php

namespace App\Filament\Resources\AccommodationResource\Pages;

use App\Filament\Resources\AccommodationResource;
use App\Models\Accommodation;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAccommodation extends ViewRecord
{
    protected static string $resource = AccommodationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('back')
                ->url(AccommodationResource::getUrl())
                ->button()
                ->color('danger'),
        ];
    }
}

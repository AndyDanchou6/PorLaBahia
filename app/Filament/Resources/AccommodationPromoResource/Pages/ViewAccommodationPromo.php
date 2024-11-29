<?php

namespace App\Filament\Resources\AccommodationPromoResource\Pages;

use App\Filament\Resources\AccommodationPromoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAccommodationPromo extends ViewRecord
{
    protected static string $resource = AccommodationPromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('warning'),
            Actions\Action::make('back')
                ->url(AccommodationPromoResource::getUrl())
                ->button()
                ->color('gray'),
        ];
    }
}

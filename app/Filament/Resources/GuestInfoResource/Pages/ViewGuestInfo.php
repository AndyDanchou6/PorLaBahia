<?php

namespace App\Filament\Resources\GuestInfoResource\Pages;

use App\Filament\Resources\GuestInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGuestInfo extends ViewRecord
{
    protected static string $resource = GuestInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('back')
                ->url(GuestInfoResource::getUrl())
                ->button()
                ->color('danger'),
        ];
    }
}

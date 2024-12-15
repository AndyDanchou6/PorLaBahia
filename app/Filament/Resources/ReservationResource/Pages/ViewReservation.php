<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewReservation extends ViewRecord
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('warning')
                ->visible(function ($record) {
                    if ($record->booking_status === 'cancelled' || $record->booking_status === 'expired' || $record->booking_status === 'finished') {
                        return false;
                    }
                    return true;
                }),
            Actions\Action::make('back')
                ->url(ReservationResource::getUrl())
                ->button()
                ->color('gray'),
        ];
    }
}

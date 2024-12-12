<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditReservation extends EditRecord
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
            Action::make('Cancel Booking')
                ->color('danger')
                ->requiresConfirmation()
                ->modalDescription('Are you sure you\'d like to cancel this booking? This cannot be undone.')
                ->action(function ($record) {
                    $record->booking_status = 'cancelled';
                    $record->save();
                }),
            Actions\Action::make('back')
                ->url(ReservationResource::getUrl())
                ->button()
                ->color('gray'),
        ];
    }
}

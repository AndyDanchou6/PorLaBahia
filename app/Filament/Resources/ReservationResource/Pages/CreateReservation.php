<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReservation extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = ReservationResource::class;

    public function getSteps(): array
    {
        return [
            \Filament\Forms\Components\Wizard\Step::make('Check Availability')
                ->schema([
                    \App\Filament\Resources\ReservationResource::getCheckAvailabilityForm(),
                    \App\Filament\Resources\ReservationResource::getAvailableDatesForm(),
                ])->columns(3)
                ->afterValidation(function ($get, $set) {
                    $onHoldExpirationDate = \Illuminate\Support\Carbon::now()->addDays(12)->startOfMinute();

                    $set('on_hold_expiration_date', $onHoldExpirationDate->format('M d, Y H:i'));
                }),

            \Filament\Forms\Components\Wizard\Step::make('Summary')
                ->schema([
                    \App\Filament\Resources\ReservationResource::getSummaryForm(),
                ]),
        ];
    }
}

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
                ->icon('heroicon-o-magnifying-glass'),

            \Filament\Forms\Components\Wizard\Step::make('Summary')
                ->schema([
                    \App\Filament\Resources\ReservationResource::getSummaryForm(),
                    \App\Filament\Resources\ReservationResource::getHiddenField(),
                ])
                ->icon('heroicon-o-clipboard-document-list'),

            \Filament\Forms\Components\Wizard\Step::make('Choose Payment')
                ->schema([
                    \App\Filament\Resources\ReservationResource::getPaymentType(),
                ])
                ->icon('heroicon-o-banknotes'),
        ];
    }
}

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
                ->beforeValidation(function ($get, $set) {
                    $checkInDate = \Illuminate\Support\Carbon::parse($get('check_in_date'));
                    $checkOutDate = \Illuminate\Support\Carbon::parse($get('check_out_date'));
                    $accommodation = \App\Models\Accommodation::find($get('accommodation_id'));
                    $stayDuration = $checkInDate->diffInDays($checkOutDate);
                    $bookingFee = $accommodation->booking_fee * $stayDuration;
                    $onHoldExpirationDate = \Illuminate\Support\Carbon::now()->addDays(12)->startOfMinute();

                    $set('booking_fee', $bookingFee);
                    $set('check_in_date', $checkInDate->format('M d, Y'));
                    $set('check_out_date', $checkOutDate->format('M d, Y'));
                    $set('on_hold_expiration_date', $onHoldExpirationDate->format('M d, Y H:i'));
                }),

            \Filament\Forms\Components\Wizard\Step::make('Summary')
                ->schema([
                    \App\Filament\Resources\ReservationResource::getSummaryForm(),
                ]),
        ];
    }
}

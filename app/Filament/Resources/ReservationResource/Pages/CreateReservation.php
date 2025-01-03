<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateReservation extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = ReservationResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $data['on_hold_expiration_date'] = Carbon::now()->addHours(12);
        $data['booking_status'] = 'on_hold';

        // $checkIn = $data['check_in_date'];
        // $checkOut = $data['check_out_date'];

        // $onPromo = \App\Models\AccommodationPromo::where('accommodation_id', $data['accommodation_id'])
        //     ->where(function ($query) use ($checkIn, $checkOut) {
        //         $query->whereRaw('? BETWEEN DATE(promo_start_date) AND DATE(promo_end_date)', [$checkIn])
        //             ->orWhereRaw('? BETWEEN DATE(promo_start_date) AND DATE(promo_end_date)', [$checkOut])
        //             ->orWhereRaw('DATE(promo_start_date) BETWEEN ? AND ?', [$checkIn, $checkOut])
        //             ->orWhereRaw('DATE(promo_end_date) BETWEEN ? AND ?', [$checkIn, $checkOut]);
        //     })
        //     ->get();

        // dd($onPromo);
        return static::getModel()::create($data);
    }

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
                ])
                ->icon('heroicon-o-clipboard-document-list'),

            \Filament\Forms\Components\Wizard\Step::make('Choose Payment')
                ->schema([
                    \App\Filament\Resources\ReservationResource::getPaymentType(),
                ])
                ->icon('heroicon-o-banknotes'),
        ];
    }

    protected function getCreatedNotification(): ?Notification
    {
        $accommodation = $this->record->accommodation->room_name;
        $guest = $this->record->guest->full_name;
        $check_in_date = Carbon::parse($this->record->check_in_date)->format('M d, Y');
        $check_out_date = Carbon::parse($this->record->check_out_date)->format('M d, Y');

        return Notification::make()
            ->title('New Booking Created')
            ->body("$guest has booked $accommodation on $check_in_date to $check_out_date")
            ->icon('heroicon-o-document-text')
            ->iconColor('success')
            ->actions([
                \Filament\Notifications\Actions\Action::make('view')
                    ->url(ReservationResource::getUrl('view', ['record' => $this->record->id])),
            ])
            ->sendToDatabase(auth()->user());
    }
}

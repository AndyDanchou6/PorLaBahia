<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ReservationResource\RelationManagers\PaymentsRelationManager;
use App\Models\GuestInfo;
use App\Models\Payment;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Support\RawJs;
use Filament\Tables\Columns\Summarizers\Sum;

class EditReservation extends EditRecord
{
    use EditRecord\Concerns\HasWizard;

    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(function ($record) {
                    $hideIn = ['finished', 'cancelled', 'expired'];

                    return in_array($record->booking_status, $hideIn);
                }),

            Actions\Action::make('back')
                ->url(ReservationResource::getUrl())
                ->button()
                ->color('gray'),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        $bookingReferenceNo = $this->record->booking_reference_no;
        $guest = $this->record->guest->full_name;

        return Notification::make()
            ->success()
            ->title('Booking has been updated')
            ->body("$guest updated his booking $bookingReferenceNo")
            ->sendToDatabase(auth()->user());
    }

    public function getStartStep(): int
    {
        return 2;
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
                ->icon('heroicon-o-banknotes')
                ->visible(function ($record) {
                    $hasPayment = Payment::where('reservation_id', $record->id)
                        ->whereNotIn('payment_status', ['void'])
                        ->get();

                    if ($hasPayment->isEmpty()) {
                        return true;
                    }

                    return false;
                }),
        ];
    }

    public function hasSkippableSteps(): bool
    {
        return true;
    }
}

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
            Action::make('Cancel Booking')
                ->color('danger')
                ->visible(fn($record) => $record->booking_status === 'active')
                ->requiresConfirmation()
                ->modalDescription('Are you sure you\'d like to cancel this booking? This cannot be undone.')
                ->action(function ($record) {
                    $record->booking_status = 'cancelled';

                    if ($record->save()) {
                        return \Filament\Notifications\Notification::make()
                            ->title($record->booking_reference_no . ' has been cancelled')
                            ->danger()
                            ->duration(5000)
                            ->send();
                    }
                }),

            Actions\Action::make('back')
                ->url(ReservationResource::getUrl())
                ->button()
                ->color('gray'),
        ];
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
        ];
    }

    public function hasSkippableSteps(): bool
    {
        return true;
    }
}

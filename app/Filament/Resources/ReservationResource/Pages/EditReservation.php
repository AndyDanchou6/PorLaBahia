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
            \Filament\Actions\Action::make('Payment')
                ->label('Pay')
                ->color('success')
                ->icon('heroicon-o-credit-card')
                ->form([
                    \Filament\Forms\Components\TextInput::make('amount')
                        ->hint(function ($record) {

                            $record = $this->getRecord();

                            $getBalance = Payment::where('reservation_id', $record->id)->whereNotIn('payment_status', ['void', 'unpaid'])->sum('amount');

                            $remainingBalance = $record->booking_fee - $getBalance;

                            if ($remainingBalance) {
                                return "Remaining Payable: ₱{$remainingBalance}.00";
                            }

                            return false;
                        })
                        ->hintColor('success')
                        ->numeric()
                        ->minValue(1)
                        ->reactive()
                        ->mask(RawJs::make('$money($input)'))
                        ->stripCharacters(',')
                        ->prefix('₱')
                        ->required()
                        ->reactive()
                        ->rules([
                            fn(): Closure => function (string $attribute, $value, Closure $fail) {
                                $record = $this->getRecord();

                                $getAmount = Payment::where('reservation_id', $record->id)->whereNotIn('payment_status', ['void', 'unpaid'])->sum('amount');

                                $bookingFee = $record->booking_fee;

                                $newTotal = $getAmount + $value;

                                $remainingBalance = $bookingFee - $getAmount;

                                if ($newTotal > $bookingFee) {
                                    $fail("The amount exceeds your remaining payable of ₱{$remainingBalance}.00. Please enter the correct amount.");
                                }
                            }
                        ]),

                    \Filament\Forms\Components\Select::make('payment_method')
                        ->options(function ($get) {

                            $guest_id = $this->getRecord()->guest_id;
                            $guest = GuestInfo::find($guest_id);
                            $credits = $guest->guestCredit->first();

                            if ($credits) {
                                $creditAmount = $credits->amount;

                                if ($creditAmount >= $get('amount')) {
                                    return [
                                        'cash' => 'Cash',
                                        'GCash' => 'GCash',
                                        'credits' => 'Credits',
                                    ];
                                } else {
                                    return [
                                        'cash' => 'Cash',
                                        'GCash' => 'GCash',
                                    ];
                                }
                            } else {
                                return [
                                    'cash' => 'Cash',
                                    'GCash' => 'GCash',
                                ];
                            }
                        })
                        ->required()
                        ->reactive(),

                    \Filament\Forms\Components\TextInput::make('gcash_reference_number')
                        ->visible(function ($get) {
                            $paymentMethod = $get('payment_method');

                            if ($paymentMethod == 'GCash') {
                                return true;
                            }

                            return false;
                        })
                        ->reactive()
                        ->label('Gcash Reference #')
                        ->required(),

                    \Filament\Forms\Components\FileUpload::make('gcash_screenshot')
                        ->visible(function ($get) {
                            $paymentMethod = $get('payment_method');

                            if ($paymentMethod == 'GCash') {
                                return true;
                            }

                            return false;
                        })
                        ->image()
                        ->reactive()
                        ->label('Gcash Screenshot'),

                ])->action(function (array $data, $record): void {
                    $payment = Payment::create([
                        'reservation_id' => $record->id,
                        'amount' => $data['amount'],
                        'payment_method' => $data['payment_method'],
                        'gcash_reference_number' => $data['gcash_reference_number'] ?? null,
                        'gcash_screenshot' => $data['gcash_screenshot'] ?? null,
                    ]);

                    $payment->save();

                    Notification::make()
                        ->success()
                        ->title('Payment Success')
                        ->body('Payment has been successfully recorded.')
                        ->send();

                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $record->id]));
                })
                ->visible(function ($record) {
                    $record = $this->getRecord();

                    $getAmount = Payment::where('reservation_id', $record->id)->where('payment_status', '!=', 'void')->sum('amount');

                    $bookingFee = $record->booking_fee;

                    if ($getAmount == $bookingFee) {
                        return false;
                    }

                    return true;
                })
                ->modalWidth('2xl')
                ->modalHeading('Payment'),

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

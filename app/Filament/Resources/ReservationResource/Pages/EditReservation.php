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
use Filament\Tables\Columns\Summarizers\Sum;

class EditReservation extends EditRecord
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Payment')
                ->label('Pay')
                ->color('success')
                ->icon('heroicon-o-credit-card')
                ->form([
                    TextInput::make('amount')
                        ->hint(function ($record) {

                            $record = $this->getRecord();

                            $getBalance = Payment::where('reservation_id', $record->id)->where('payment_status', '!=', 'void')->sum('amount');

                            $remainingBalance = $record->booking_fee - $getBalance;

                            if ($remainingBalance) {
                                return "Remaining Payable: ₱{$remainingBalance}.00";
                            }

                            return false;
                        })
                        ->hintColor('success')
                        ->numeric()
                        ->prefix('₱')
                        ->required()
                        ->reactive()
                        ->rules([
                            fn(): Closure => function (string $attribute, $value, Closure $fail) {
                                $record = $this->getRecord();

                                $getAmount = Payment::where('reservation_id', $record->id)->where('payment_status', '!=', 'void')->sum('amount');

                                $bookingFee = $record->booking_fee;

                                $newTotal = $getAmount + $value;

                                $remainingBalance = $bookingFee - $getAmount;

                                if ($newTotal > $bookingFee) {
                                    $fail("Your payment exceeds the booking fee of ₱{$bookingFee}. Your remaining payable is ₱{$remainingBalance}.00");
                                }
                            }
                        ]),

                    Select::make('payment_method')
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

                    TextInput::make('gcash_reference_number')
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

                    FileUpload::make('gcash_screenshot')
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
                })->visible(function ($record) {
                    $record = $this->getRecord();

                    $getAmount = Payment::where('reservation_id', $record->id)->where('payment_status', '!=', 'void')->sum('amount');

                    $bookingFee = $record->booking_fee;

                    if ($getAmount == $bookingFee) {
                        return false;
                    }

                    return true;
                })->modalWidth('2xl')
                ->modalHeading('Payment'),

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

<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use App\Models\GuestCredit;
use App\Models\GuestInfo;
use App\Models\Payment;
use Closure;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\ImageEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Alignment;
use Filament\Support\RawJs;
use Illuminate\Support\Carbon;

class ViewReservation extends ViewRecord
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('pay')
                ->icon('heroicon-o-credit-card')
                ->form([
                    // Full Payment Form
                    \Filament\Forms\Components\Fieldset::make()
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('amount')
                                ->default(fn($record) => $record->booking_fee)
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->prefix('₱')
                                ->hint(
                                    function ($record) {
                                        $record = $this->getRecord();

                                        $getBalance = Payment::where('reservation_id', $record->id)->whereNotIn('payment_status', ['void', 'unpaid'])->sum('amount');

                                        $remainingBalance = $record->booking_fee - $getBalance;

                                        if ($remainingBalance) {
                                            return "Payable: ₱{$remainingBalance}.00";
                                        }

                                        return false;
                                    }
                                )
                                ->hintColor('success')
                                ->readOnly(),

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
                                            'xendit' => 'Xendit'
                                        ];
                                    }
                                })
                                ->label('Payment Method')
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
                                ->required()
                                ->columnSpan('full'),

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
                                ->label('Gcash Screenshot')
                                ->columnSpan('full'),
                        ])->visible(fn($record) => $record->payment_type == 'straight_payment'),


                    // Split Payment Form
                    \Filament\Forms\Components\Fieldset::make()
                        ->schema([
                            \Filament\Forms\Components\Repeater::make('payment_form')
                                ->schema([
                                    \Filament\Forms\Components\Grid::make(2)
                                        ->schema([
                                            \Filament\Forms\Components\TextInput::make('amount')
                                                ->prefix('₱')
                                                ->required()
                                                ->reactive(),

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
                                                            'xendit' => 'Xendit'
                                                        ];
                                                    }
                                                })
                                                ->label('Payment Method')
                                                ->required()
                                                ->reactive(),

                                        ]),

                                    \Filament\Forms\Components\Grid::make(1)
                                        ->schema([
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
                                                ->downloadable()
                                                ->reactive()
                                                ->label('Gcash Screenshot'),
                                        ]),
                                ])
                                ->rule(function (\Filament\Forms\Get $get, $record) {
                                    return [
                                        function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                                            $paymentFormData = $get('payment_form');

                                            if (is_array($paymentFormData)) {
                                                $totalAmount = 0;

                                                foreach ($paymentFormData as $data) {
                                                    $totalAmount += (float) ($data['amount'] ?? 0);
                                                }

                                                $getBalance = Payment::where('reservation_id', $record->id)->where('payment_status', '!=', 'void')->sum('amount');

                                                $getAmount = $getBalance + $totalAmount;

                                                $remainingBalance = $record->booking_fee - $getAmount;

                                                if ($remainingBalance < 0) {
                                                    $fail("The total amount entered exceeds the remaining payable by ₱" . abs($remainingBalance) . ".00. Please enter a valid amount.");
                                                }
                                            }
                                        },
                                    ];
                                })
                                ->hint(
                                    function ($get, $record) {
                                        $getBalance = Payment::where('reservation_id', $record->id)->where('payment_status', '!=', 'void')->sum('amount');

                                        $value = collect($get('payment_form'))->pluck('amount')
                                            ->map(fn($amount) => (float) $amount)
                                            ->sum();

                                        $getAmount = $getBalance + $value;

                                        $remainingBalance = $record->booking_fee - $getAmount;

                                        if ($remainingBalance > 0) {
                                            return "Payable: ₱{$remainingBalance}.00";
                                        } elseif ($remainingBalance == 0) {
                                            return "Fully Paid";
                                        } else {
                                            return "Overpayment";
                                        }
                                    }
                                )
                                ->reactive()
                                ->hintColor('success')
                                ->columnSpan('full')
                                ->label('Payment Form')
                                ->reorderableWithButtons()
                                ->collapsed(false)
                                ->cloneable()
                                ->addActionLabel('Create another payment')
                                ->addActionAlignment(Alignment::End)
                        ])
                        ->visible(fn($record) => $record->payment_type == 'split_payment'),

                ])
                ->action(function (array $data, $record): void {
                    if (isset($data['payment_form']) && is_array($data['payment_form'])) {
                        $existingPayments = Payment::where('reservation_id', $record->id)
                            ->whereNotIn('payment_status', ['void'])
                            ->get();
                        $totalPaid = 0;

                        if (!$existingPayments->isEmpty()) {
                            foreach ($existingPayments as $payment) {
                                $totalPaid = $totalPaid + $payment->amount;
                            }
                        }

                        foreach ($data['payment_form'] as $dataArray) {
                            $gcashScreenshot = null;
                            $totalPaid = $totalPaid + $dataArray['amount'];

                            if (!empty($dataArray['gcash_screenshot'])) {
                                $gcashScreenshot = $dataArray['gcash_screenshot'];
                            }

                            Payment::create([
                                'reservation_id' => $record->id,
                                'amount' => $dataArray['amount'],
                                'payment_method' => $dataArray['payment_method'],
                                'payment_status' => 'paid',
                                'gcash_reference_number' => $dataArray['gcash_reference_number'] ?? null,
                                'gcash_screenshot' => $gcashScreenshot ?? null,
                            ]);
                        }

                        if ($totalPaid == $record->getOriginal('booking_fee')) {
                            $record->booking_status = 'active';
                        } else {
                            $record->booking_status = 'pending';
                        }

                        $record->save();
                    } else {
                        $query = Payment::create([
                            'reservation_id' => $record->id,
                            'amount' => $data['amount'],
                            'payment_method' => $data['payment_method'],
                            'payment_status' => 'paid',
                            'gcash_reference_number' => $data['gcash_reference_number'] ?? null,
                            'gcash_screenshot' => $data['gcash_screenshot'] ?? null,
                        ]);

                        $record->booking_status = 'active';
                        $record->save();
                        $query->save();
                    }

                    Notification::make()
                        ->success()
                        ->title('Payment Success')
                        ->body('Payment has been successfully recorded.')
                        ->send();

                    $this->redirect($this->getResource()::getUrl('view', ['record' => $record->id]));
                })
                ->visible(function ($record) {
                    $record = $this->getRecord();

                    $getAmount = Payment::where('reservation_id', $record->id)->where('payment_status', '!=', 'void')->sum('amount');

                    $bookingFee = $record->booking_fee;

                    if ($getAmount == $bookingFee) {
                        return false;
                    }

                    if ($record->booking_status == 'cancelled') {
                        return false;
                    }

                    return true;
                })
                ->color('success')
                ->modalWidth('2xl')
                ->modalHeading(function ($record) {
                    if ($record->payment_type == 'straight_payment') {
                        return 'Straight Payment Form';
                    } elseif ($record->payment_type == 'split_payment') {
                        return 'Split Payment';
                    }

                    return 'Pay Here';
                })->slideOver(),

            // Cancel Booking Button
            Action::make('Cancel Booking')
                ->color('danger')
                ->visible(fn($record) => $record->booking_status === 'active')
                ->requiresConfirmation()
                ->modalDescription('Are you sure you\'d like to cancel this booking? This cannot be undone.')
                ->action(function ($record) {
                    $record->booking_status = 'cancelled';
                    if (!$record->save()) {
                        \Filament\Notifications\Notification::make()
                            ->title("Booking status not updated")
                            ->body("Error saving updating booking status from active to cancelled")
                            ->danger()
                            ->duration(5000)
                            ->send();
                    }

                    $checkIn = Carbon::parse($record->check_in_date);
                    $now = Carbon::now();
                    $totalCredits = 0;
                    $dayLimit = 10;
                    $payments = Payment::where('reservation_id', $record->id)
                        ->where('payment_status', 'paid')
                        ->get();

                    // if cancelled 10 days prior to reservation
                    if ($now->diffInDays($checkIn) >= $dayLimit) {
                        if (!$payments->isEmpty()) {
                            foreach ($payments as $payment) {
                                $totalCredits = $totalCredits + $payment->amount;
                            }

                            $payment->save();
                        }

                        $bookingSuffix = substr($record->booking_reference_no, 13);
                        $expirationDate = Carbon::now()->addYear();
                        $guestCredit = GuestCredit::create([
                            'guest_id' => $record->guest_id,
                            'coupon' => GuestCredit::generateCoupon($bookingSuffix),
                            'amount' => $totalCredits,
                            'expiration_date' => $expirationDate,
                            'status' => 'active',
                        ]);

                        if (!$guestCredit->save()) {
                            \Filament\Notifications\Notification::make()
                                ->title("Credit not recorded")
                                ->body("Error saving credit")
                                ->danger()
                                ->duration(5000)
                                ->send();
                        }
                    }

                    // void payments after cancellation
                    foreach ($payments as $payment) {
                        $payment->payment_status = 'void';
                        $payment->save();
                    }

                    if ($totalCredits > 0) {
                        $guest = $record->guest->fullname;

                        \Filament\Notifications\Notification::make()
                            ->title("Booking cancelled and credits been recorded")
                            ->body("$guest cancelled their booking #$record->booking_reference_no and Php $totalCredits payment have been moved to guest credits.")
                            ->info()
                            ->duration(5000)
                            ->send();
                    } else {
                        \Filament\Notifications\Notification::make()
                            ->title($record->booking_reference_no . ' has been cancelled')
                            ->danger()
                            ->duration(5000)
                            ->send();
                    }

                    $this->redirect($this->getResource()::getUrl('view', ['record' => $record->id]));
                }),

            // EditAction Button
            Actions\EditAction::make()
                ->color('warning')
                ->visible(function ($record) {
                    if ($record->booking_status === 'cancelled' || $record->booking_status === 'expired' || $record->booking_status === 'finished') {
                        return false;
                    }
                    return true;
                }),

            // Back Button
            Actions\Action::make('back')
                ->url(ReservationResource::getUrl())
                ->button()
                ->color('gray'),
        ];
    }
}

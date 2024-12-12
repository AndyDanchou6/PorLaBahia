<?php

namespace App\Filament\Resources\ReservationResource\RelationManagers;

use App\Models\GuestInfo;
use App\Models\Payment;
use Carbon\Carbon;
use Closure;
use Dotenv\Parser\Value;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->prefix('₱')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($set) {
                        $set('payment_method', null);
                    })
                    ->helperText(function () {
                        $record = $this->getOwnerRecord();

                        $isFirstPayment = Payment::where('reservation_id', $record->id)->doesntExist();

                        if ($isFirstPayment) {
                            return 'Reminder: Please settle the booking fee payment first.';
                        } else {
                            return null;
                        }
                    })
                    ->rules([
                        fn(): Closure => function (string $attribute, $value, Closure $fail, $record) {
                            $reservationRecord = $this->getOwnerRecord();

                            if (!$reservationRecord) {
                                $fail('The reservation record could not be found.');
                                return;
                            }

                            $bookingFeeRequirements = $reservationRecord->booking_fee;

                            $isFirstPayment = Payment::where('reservation_id', $reservationRecord->id)->doesntExist();

                            if ($isFirstPayment) {
                                if ($value < $bookingFeeRequirements) {
                                    $fail("The initial payment of ₱{$value}.00 is insufficient to cover the required booking fee of ₱{$bookingFeeRequirements}. Please settle the full booking fee amount to proceed.");
                                }
                            } else {
                                if ($value < $bookingFeeRequirements) {
                                    $fail("The initial payment of ₱{$value}.00 is insufficient to cover the required booking fee of ₱{$bookingFeeRequirements}. Please settle the full booking fee amount to proceed.");
                                }
                            }

                            dd($record);
                        }
                    ]),

                Forms\Components\Select::make('payment_method')
                    ->live()
                    ->options(function ($get) {

                        $guest_id = $this->getOwnerRecord()->guest_id;
                        $guest = GuestInfo::find($guest_id);
                        $credits = $guest->guestCredit->first();

                        if ($credits) {
                            $creditAmount = $credits->amount;

                            if ($creditAmount >= $get('amount')) {
                                return [
                                    'cash' => 'Cash',
                                    'g-cash' => 'G-Cash',
                                    'credits' => 'Credits',
                                ];
                            } else {
                                return [
                                    'cash' => 'Cash',
                                    'g-cash' => 'G-Cash',
                                ];
                            }
                        } else {
                            return [
                                'cash' => 'Cash',
                                'g-cash' => 'G-Cash',
                            ];
                        }
                    })
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Payment')
            ->columns([
                // Tables\Columns\TextColumn::make('reservation_id'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->prefix('₱'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->badge()
                    ->searchable()
                    ->color(fn(string $state): string => match ($state) {
                        'g-cash' => 'success',
                        'cash' => 'info',
                        'credits' => 'warning',
                    })
                    ->label('Payment Method'),
            ])
            ->filters([
                //
                Tables\Filters\TrashedFilter::make()
                    ->visible(fn() => Auth::user()->role == 1),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('New Payment'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }
}

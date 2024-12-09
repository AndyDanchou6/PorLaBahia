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
                    ->live()
                    ->rules([
                        fn(): Closure => function (string $attribute, $value, Closure $fail) {
                            $record = $this->getOwnerRecord();

                            if (!$record) {
                                $fail('The reservation record could not be found.');
                                return;
                            }

                            $bookingFeeRequirements = $record->booking_fee;

                            $isFirstPayment = Payment::where('reservation_id', $record->id)->doesntExist();

                            if ($isFirstPayment && $value < $bookingFeeRequirements) {
                                $fail("The payment amount ₱{$value} is less than the required booking fee ₱{$bookingFeeRequirements}.");
                            }
                        }
                    ]),

                Forms\Components\Select::make('payment_method')
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
                    ->color(fn(string $state): string => match ($state) {
                        'g-cash' => 'success',
                        'cash' => 'info',
                        'credits' => 'warning',
                    })
                    ->label('Payment Method'),
            ])
            ->filters([
                //
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

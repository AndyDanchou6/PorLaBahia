<?php

namespace App\Filament\Resources\ReservationResource\RelationManagers;

use App\Filament\Resources\ReservationResource;
use App\Models\GuestInfo;
use App\Models\Payment;
use Carbon\Carbon;
use Closure;
use Dotenv\Parser\Value;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('amount')
                    ->numeric()
                    ->prefix('₱')
                    ->required()
                    ->reactive()
                    ->rules([
                        fn(): Closure => function (string $attribute, $value, Closure $fail) {
                            $record = $this->getOwnerRecord();

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

                        $guest_id = $this->getOwnerRecord()->guest_id;
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

                TextInput::make('payment_status')
                    ->reactive()
                    ->label('Payment Status')
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
                    ->label('Gcash Screenshot')
                    ->columnSpan('full'),
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
                        'GCash' => 'success',
                        'cash' => 'info',
                        'credits' => 'warning',
                    })
                    ->label('Payment Method'),

                Tables\Columns\TextColumn::make('payment_status')
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->badge()
                    ->searchable()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                        'void' => 'gray',
                    })
                    ->label('Payment Status'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                    ->visible(fn() => Auth::user()->role == 1),
            ])
            ->headerActions([
                //
            ])
            ->actions([

                // Action::make('View')
                //     ->form([
                //         TextInput::make('amount')
                //             ->label('Amount')
                //             ->disabled()
                //             ->default(function ($record) {
                //                 return $record->amount;
                //             }),
                //     ])
                //     ->slideOver(),

                Tables\Actions\ViewAction::make(),

                Action::make('Paid')
                    ->icon('heroicon-o-credit-card')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm GCash Payment')
                    ->modalDescription('Are you sure you want to mark this reservation as paid via GCash? This action cannot be undone.')
                    ->modalSubmitActionLabel('Yes, Mark as Paid')
                    ->action(function ($record) {
                        if ($record) {
                            $record->payment_status = 'paid';
                        }

                        $record->save();

                        Notification::make()
                            ->title('GCash Payment Successful')
                            ->body('The reservation payment has been marked as successfully paid via GCash.')
                            ->success()
                            ->send();

                        $this->redirect(ReservationResource::getUrl('edit', ['record' => $record->reservation_id]));
                    })
                    ->visible(function ($record) {
                        if ($record->payment_status == 'paid' && $record->payment_status = 'void') {
                            return false;
                        }

                        if ($record->payment_method == 'GCash' && $record->payment_status == 'unpaid') {
                            return true;
                        }

                        return;
                    })
                    ->color('success'),

                Action::make('void')
                    ->color('warning')
                    ->icon('heroicon-o-trash')
                    ->action(function ($record) {
                        if ($record) {
                            $record->payment_status = 'void';
                        }

                        $record->save();

                        $this->redirect(ReservationResource::getUrl('edit', ['record' => $record->reservation_id]));
                    })
                    ->visible(function ($record) {
                        if ($record->payment_status == 'void') {
                            return false;
                        }

                        return true;
                    }),


            ])->defaultSort(function ($query) {
                $query->orderByRaw("FIELD(payment_status, 'paid', 'unpaid', 'void')");
            });
    }

    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             Infolists\Components\TextEntry::make('name'),
    //             Infolists\Components\TextEntry::make('email'),
    //             Infolists\Components\TextEntry::make('notes')
    //                 ->columnSpanFull(),
    //         ]);
    // }
}

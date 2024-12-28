<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Models\Accommodation;
use App\Models\GuestCredit;
use App\Models\GuestInfo;
use Illuminate\Support\Carbon;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Infolists\Components\Fieldset as ComponentsFieldset;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static $dateFormat = 'M d, Y h:i a';

    protected static ?string $recordTitleAttribute = 'booking_reference_no';

    public static function getNavigationBadge(): ?string
    {
        $count = Reservation::whereNull('deleted_at')
            ->count();

        if ($count == 0) {
            return null;
        }

        return $count;
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Fieldset::make('Booking Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('check_in_date')
                            ->color('primary')
                            ->formatStateUsing(fn($record) => Carbon::parse($record->check_in_date)->addHours(11)->format(self::$dateFormat)),
                        Infolists\Components\TextEntry::make('check_out_date')
                            ->color('primary')
                            ->formatStateUsing(fn($record) => Carbon::parse($record->check_out_date)->addHours(9)->format(self::$dateFormat)),
                        Infolists\Components\TextEntry::make('accommodation.room_name')
                            ->color('primary'),
                        Infolists\Components\TextEntry::make('guest.full_name')
                            ->color('primary'),
                        Infolists\Components\TextEntry::make('booking_reference_no')
                            ->color('primary'),
                        Infolists\Components\TextEntry::make('booking_fee')
                            ->money('PHP')
                            ->color('primary'),
                        Infolists\Components\TextEntry::make('booking_status')
                            ->formatStateUsing(function ($record) {
                                switch ($record->booking_status) {
                                    case 'active':
                                        return 'Active';
                                        break;
                                    case 'finished':
                                        return 'Finished';
                                        break;
                                    case 'on_hold':
                                        return 'On Hold';
                                        break;
                                    case 'expired':
                                        return 'Expired';
                                        break;
                                    case 'pending':
                                        return 'Pending';
                                        break;
                                    case 'cancelled':
                                        return 'Cancelled';
                                        break;
                                    case null:
                                        return 'Unknown Status';
                                        break;
                                }
                            })
                            ->badge()
                            ->color(function ($record) {
                                switch ($record->booking_status) {
                                    case 'active':
                                        return 'success';
                                        break;
                                    case 'finished':
                                        return 'gray';
                                        break;
                                    case 'on_hold':
                                        return 'info';
                                        break;
                                    case 'expired':
                                        return 'danger';
                                        break;
                                    case 'pending':
                                        return 'info';
                                        break;
                                    case 'cancelled':
                                        return 'gray';
                                        break;
                                }
                            }),

                        Infolists\Components\TextEntry::make('on_hold_expiration_date')
                            ->formatStateUsing(fn($record) => Carbon::parse($record->on_hold_expiration_date)->format(self::$dateFormat))
                            ->color('danger')
                            ->visible(fn($record) => $record->on_hold_expiration_date && $record->booking_status == 'on_hold' || $record->booking_status == 'pending'),

                        Infolists\Components\TextEntry::make('payment_type')
                            ->formatStateUsing(function ($record) {
                                return match ($record->payment_type) {
                                    'straight_payment' => 'Straight Payment',
                                    'split_payment' => 'Split Payment',
                                    default => 'Unknown',
                                };
                            })
                            ->color('primary'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_reference_no')
                    ->searchable(),
                TextColumn::make('accommodation.room_name')
                    ->label('Accommodation')
                    ->searchable()
                    ->formatStateUsing(function ($record) {
                        return $record->accommodation->room_name;
                    }),
                TextColumn::make('guest_id')
                    ->label('Guest Name')
                    ->formatStateUsing(function ($record) {
                        return $record->guest->full_name;
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('guest', function ($guestQuery) use ($search) {
                            $guestQuery->where('first_name', 'like', '%' . $search . '%')
                                ->orWhere('last_name', 'like', '%' . $search . '%');
                        });
                    }),

                TextColumn::make('check_in_date')
                    ->dateTime()
                    ->formatStateUsing(fn($record) => Carbon::parse($record->check_in_date)->addHours(11)->format(self::$dateFormat))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('check_out_date')
                    ->dateTime()
                    ->formatStateUsing(fn($record) => Carbon::parse($record->check_out_date)->addHours(9)->format(self::$dateFormat))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('booking_status')
                    ->searchable()
                    ->formatStateUsing(function ($record) {
                        if ($record) {
                            switch ($record->booking_status) {
                                case 'active':
                                    return 'Active';
                                    break;
                                case 'cancelled':
                                    return 'Cancelled';
                                    break;
                                case 'on_hold':
                                    return 'On Hold';
                                    break;
                                case 'pending':
                                    return 'Pending';
                                    break;
                                case 'expired':
                                    return 'Expired';
                                    break;
                                case 'finished':
                                    return 'Finished';
                                    break;
                            }
                        }
                    }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('checked_in')
                            ->default(Carbon::today()),
                        Forms\Components\DatePicker::make('checked_out'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['checked_in'],
                                fn(Builder $query, $date): Builder => $query->whereDate('check_in_date', '>=', Carbon::parse($date)->toDateString()),
                            )
                            ->when(
                                $data['checked_out'],
                                fn(Builder $query, $date): Builder => $query->whereDate('check_out_date', '<=', Carbon::parse($date)->toDateString()),
                            );
                    }),
                SelectFilter::make('payment_type')
                    ->options([
                        'straight_payment' => 'Straight Payment',
                        'split_payment' => 'Split Payment',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->visible(function ($record) {
                            return !$record->trashed();
                        }),
                    Tables\Actions\EditAction::make()
                        ->visible(function ($record) {
                            if ($record->booking_status === 'expired' || $record->booking_status === 'cancelled') {
                                return false;
                            }
                            return true;
                        })
                        ->color('warning'),
                    Tables\Actions\RestoreAction::make()
                        ->visible(function ($record) {
                            return auth()->check() && auth()->user()->role === 1 && $record->trashed();
                        })
                        ->color('success'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    // Tables\Actions\RestoreBulkAction::make()
                    //     ->visible(function () {
                    //         return auth()->check() && auth()->user()->role === 1;
                    //     }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'view' => Pages\ViewReservation::route('/{record}'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }

    public static function getCheckAvailabilityForm()
    {
        return Forms\Components\Section::make()
            ->schema([
                Forms\Components\DatePicker::make('check_in_date_picker')
                    ->label('Select check in date')
                    ->required(fn($operation) => $operation === 'create')
                    ->date()
                    ->minDate(today())
                    ->live(debounce: 100)
                    // ->native(false)
                    ->afterStateUpdated(function ($set, $get) {
                        if ($get('check_out_date_picker')) {
                            return $set('check_out_date_picker', null);
                        }

                        return;
                    }),

                Forms\Components\DatePicker::make('check_out_date_picker')
                    ->label('Select check out date')
                    ->required(fn($operation) => $operation === 'create')
                    ->date()
                    ->minDate(function ($get) {
                        $checkInDate = $get('check_in_date_picker');
                        if ($checkInDate) {
                            return \Illuminate\Support\Carbon::parse($checkInDate)->addDay();
                        } else {
                            return today();
                        }
                    })
                    ->live(debounce: 100)
                    // ->native(false)
                    ->visible(fn($get) => $get('check_in_date_picker')),

                Forms\Components\Select::make('guest')
                    ->options(fn() => \App\Models\GuestInfo::all()->mapWithKeys(fn($guest) => [
                        $guest->id => $guest->first_name . ' ' . $guest->last_name,
                    ]))
                    ->createOptionForm([
                        \App\Filament\Resources\GuestInfoResource::getNewGuestForm(),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        $guestId = \App\Models\GuestInfo::create([
                            'first_name' => $data['first_name'],
                            'last_name' => $data['last_name'],
                            'contact_no' => $data['contact_no'],
                            'email' => $data['email'],
                            'address' => $data['address'],
                            'fb_name' => $data['fb_name'],
                        ])
                            ->getKey();

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('New Guest Registered!')
                            ->body($data['first_name'] . ' ' . $data['last_name'] . ' has registered')
                            ->sendToDatabase(auth()->user())
                            ->broadcast(auth()->user());

                        return $guestId;
                    })
                    ->afterStateUpdated(function ($state, $set) {
                        $guest = GuestInfo::find($state);

                        $set('guest_id', $state);
                        $set('guest_name', $guest->first_name . ' ' . $guest->last_name);
                    })
                    ->required(fn($operation) => $operation === 'create')
                    // ->selectablePlaceholder(false)
                    ->hidden(fn($operation) => $operation === 'edit')
                    ->visible(fn($get) => $get('check_in_date_picker') && $get('check_out_date_picker'))
                    ->columnSpanFull(),
            ])
            ->columns(2)
            ->columnSpan(2);
    }

    public static function getAvailableDatesForm()
    {
        return
            Forms\Components\Radio::make('Available Dates')
            ->options(fn($get, $record) => (new \App\Models\Reservation())->getAvailableAccommodations($get('check_in_date_picker'), $get('check_out_date_picker'), $record ? $record->id : null))
            ->visible(fn($get) => $get('check_in_date_picker') && $get('check_out_date_picker'))
            ->afterStateUpdated(function ($state, $set, $get) {
                $bookingInfo = explode('/', $state);
                $accommodation = Accommodation::find($bookingInfo[0]);
                $checkInDate = \Illuminate\Support\Carbon::parse($bookingInfo[1]);
                $checkOutDate = \Illuminate\Support\Carbon::parse($bookingInfo[2]);
                $stayDuration = $checkInDate->diffInDays($checkOutDate);
                $bookingFee = $accommodation->booking_fee * $stayDuration;

                $set('booking_fee', $bookingFee);
                $set('accommodation_id', $bookingInfo[0]);
                $set('accommodation_name', $accommodation->room_name);
                $set('check_in_date', $checkInDate);
                $set('check_out_date', $checkOutDate);
                $set('checkInDateDisplay', $checkInDate->format('M d, Y'));
                $set('checkOutDateDisplay', $checkOutDate->format('M d, Y'));
                // dd($checkInDate->diffInDays($checkOutDate));
            })
            ->disableOptionWhen(fn($value) => $value == null)
            ->required(fn($operation) => $operation === 'create')
            ->columnSpan(1);
    }

    public static function getPaymentType()
    {
        return
            Forms\Components\Section::make()
            ->schema([
                Forms\Components\ToggleButtons::make('payment_type')
                    ->options([
                        'straight_payment' => 'Straight Payment',
                        'split_payment' => 'Split Payment',
                    ])
                    ->columnSpanFull()
                    ->inline()
                    ->required(fn($operation) => $operation === 'create'),
            ]);
    }

    public static function getSummaryForm()
    {
        return
            Forms\Components\Section::make()
            ->schema([
                // Hidden fields
                Forms\Components\Hidden::make('check_in_date'),
                Forms\Components\Hidden::make('check_out_date'),
                Forms\Components\Hidden::make('accommodation_id'),
                Forms\Components\Hidden::make('guest_id'),

                Forms\Components\TextInput::make('checkInDateDisplay')
                    ->formatStateUsing(fn($record) => $record ? Carbon::parse($record->check_in_date)->format('M d, Y') : null)
                    ->suffix('11 am')
                    ->readOnly(),
                Forms\Components\TextInput::make('checkOutDateDisplay')
                    ->formatStateUsing(fn($record) => $record ? Carbon::parse($record->check_out_date)->format('M d, Y') : null)
                    ->suffix('9 am')
                    ->readOnly(),

                Forms\Components\TextInput::make('accommodation_name')
                    ->label('Accommodation')
                    ->formatStateUsing(function ($get) {
                        $accommodationId = $get('accommodation_id');
                        if ($accommodationId) {
                            $accommodation = Accommodation::find($accommodationId);
                            return $accommodation->room_name;
                        }
                    })
                    ->readOnly(),

                Forms\Components\TextInput::make('guest_name')
                    ->label('Guest')
                    ->formatStateUsing(function ($get) {
                        $guestId = $get('guest_id');
                        if ($guestId) {
                            $guest = GuestInfo::find($guestId);
                            return $guest->first_name . ' ' . $guest->last_name;
                        }
                    })
                    ->readOnly(),

                Forms\Components\TextInput::make('booking_reference_no')
                    ->label('Booking Reference Number')
                    ->default(fn() => (new \App\Models\Reservation())->generateBookingReference())
                    ->readOnly(),

                Forms\Components\TextInput::make('booking_fee')
                    ->numeric()
                    ->prefix('₱')
                    ->step(0.01)
                    ->readOnly(),
            ])->columns(2);
    }

    public static function getHiddenField()
    {
        return
            \Filament\Forms\Components\Group::make()
            ->schema([
                Forms\Components\Hidden::make('booking_status')
                    ->default('on_hold'),

                Forms\Components\Hidden::make('on_hold_expiration_date')
                    ->default(Carbon::now()->addHours(12)->startOfMinute()),
            ]);
    }

    public static function useCredits($record, $data)
    {
        if (isset($data['multiple'])) {
            foreach ($data['multiple'] as $creditId => $payments) {
                $credit = GuestCredit::find($creditId);
                $remainingBalance = $credit->amount;

                foreach ($payments as $payment) {
                    $remainingBalance -= $payment['amount'];
                }

                if ($remainingBalance > 0) {
                    $bookingSuffix = substr($record->booking_reference_no, 13);
                    $remainingBalance = abs($remainingBalance);

                    $newCredit = \App\Models\GuestCredit::create([
                        'guest_id' => $record->guest_id,
                        'coupon' => \App\Models\GuestCredit::generateCoupon($bookingSuffix),
                        'amount' => $remainingBalance,
                        'is_redeemed' => false,
                        'expiration_date' => Carbon::now()->addYear(),
                        'status' => 'active',
                    ]);

                    $newCredit->save();
                }

                $credit->update([
                    'is_redeemed' => true,
                    'date_redeemed' => Carbon::now(),
                    'status' => 'inactive',
                ]);

                $credit->save();
            }
        } else {
            $credit = GuestCredit::find($data['available_credits']);
            $remainingBalance = $credit->amount - $data['amount'];

            if ($remainingBalance > 0) {
                $bookingSuffix = substr($record->booking_reference_no, 13);
                $remainingBalance = abs($remainingBalance);

                $newCredit = \App\Models\GuestCredit::create([
                    'guest_id' => $record->guest_id,
                    'coupon' => \App\Models\GuestCredit::generateCoupon($bookingSuffix),
                    'amount' => $remainingBalance,
                    'is_redeemed' => false,
                    'expiration_date' => Carbon::now()->addYear(),
                    'status' => 'active',
                ]);

                $newCredit->save();
            }

            $credit->update([
                'is_redeemed' => true,
                'date_redeemed' => Carbon::now(),
                'status' => 'inactive',
            ]);

            $credit->save();
        }
    }

    public static function getAvailableCredits($guestId, $limit = 0)
    {
        $credits = '';

        if ($limit > 0) {
            $credits = GuestCredit::where('guest_id', $guestId)
                ->where('amount', '>=', $limit)
                ->where('is_redeemed', false)
                ->where('status', 'active')
                ->get();
        } elseif ($limit == 0) {
            $credits = GuestCredit::where('guest_id', $guestId)
                ->where('is_redeemed', false)
                ->where('status', 'active')
                ->get();
        }

        $availableCredits = [];

        foreach ($credits as $availableCredit) {
            $availableCredits[$availableCredit->id] = $availableCredit->coupon;
        }

        return $availableCredits;
    }
}

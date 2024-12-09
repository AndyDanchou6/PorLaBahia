<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use Illuminate\Support\Carbon;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Accommodation;
use App\Models\Discount;
use App\Models\GuestInfo;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Select::make('accommodation_id')
                            ->label('Room Name')
                            ->options(function () {
                                return Accommodation::inRandomOrder()
                                    ->limit(5)
                                    ->get()
                                    ->pluck('room_name', 'id');
                            })
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $bookingFee = Accommodation::find($state)?->booking_fee;

                                if ($bookingFee !== null) {
                                    $set('booking_fee', $bookingFee);
                                }
                            })
                            ->required(),
                        Select::make('guest_id')
                            ->label('Guest Name')
                            ->options(function () {
                                return GuestInfo::inRandomOrder()
                                    ->limit(5)
                                    ->get()
                                    ->mapWithKeys(function ($guest) {
                                        return [$guest->id => "{$guest->first_name} {$guest->last_name}"];
                                    });
                            })
                            ->searchable()
                            ->required(),
                        DatePicker::make('check_in_date')
                            ->required()
                            ->date()
                            ->minDate(today())
                            ->live()
                            ->afterStateUpdated(function ($set, $state) {
                                $set('check_out_date', Carbon::parse($state)->addDay()->format('M d, Y'));
                            })
                            ->native(false)
                            ->disabledDates(function ($get) {
                                $reservedDates = Reservation::where('deleted_at', null)
                                ->where('accommodation_id', $get('accommodation_id'))
                                ->pluck('check_in_date');

                                // $reservedDatesFormatted = $reservedDates->flatMap(function ($reservation) {
                                //     $checkInDate = Carbon::parse($reservation->check_in_date);
                                //     $checkOutDate = Carbon::parse($reservation->check_out_date);

                                //     return collect(range(0, $checkOutDate->diffInDays($checkInDate) - 1))
                                //         ->map(fn($days) => $checkInDate->copy()->addDays($days)->toDateString());
                                // });

                                $reservedDateArray = $reservedDates->toArray();

                                return $reservedDateArray;
                            }),
                        TextInput::make('check_out_date')
                            ->required()
                            ->formatStateUsing(function ($record) {
                                if ($record != null) {
                                    return Carbon::parse($record->check_out_date)->format('M d, Y');
                                }
                            })
                            ->readOnly(),

                        Select::make('discount_id')
                            ->label('Discount')
                            ->options(function ($get) {
                                $accommodation = Accommodation::find($get('accommodation_id'));
                                $checkInDate = $get('check_in_date');
                                $isWeekDay = Carbon::parse($checkInDate)->isWeekday();                                        // return Discount::where('status', true)
                                $accommodationPrice = 0;
                                if ($accommodation) {
                                    if ($isWeekDay) {
                                        $accommodationPrice = $accommodation->weekday_price;
                                    } else {
                                        $accommodationPrice = $accommodation->weekend_price;
                                    }
                                }

                                return Discount::where('status', true)
                                    ->where(function ($query) {
                                        return $query->where('usage_limit', '>', 0)
                                            ->orWhereNull('usage_limit');
                                    })
                                    ->where(function ($query) use ($accommodationPrice) {
                                        return $query->where('minimum_payable', '<=', $accommodationPrice)
                                            ->orWhere('minimum_payable', '==', 0.00);
                                    })
                                    ->where(function ($query) use ($accommodationPrice) {
                                        return $query->where('maximum_payable', '>=', $accommodationPrice)
                                            ->orWhere('maximum_payable', '==', 0.00);
                                    })
                                    ->inRandomOrder()
                                    ->limit(5)
                                    ->pluck('discount_code', 'id');
                            })
                            ->searchable()
                            ->live(100)
                            ->afterStateUpdated(function ($state, $old) {
                                if ($state !== null) {
                                    $discount = Discount::find($state);
                                    if ($discount) {
                                        $usageLimit = $discount->usage_limit;
                                        if ($usageLimit != null) {
                                            $newUsageLimit = $usageLimit - 1;
                                            $discount->update(['usage_limit' => $newUsageLimit]);
                                        }
                                    }
                                }

                                if ($old !== null && $state !== $old) {
                                    $discount = Discount::find($old);
                                    if ($discount) {
                                        if ($discount->usage_limit != null) {
                                            $newUsageLimit = $usageLimit + 1;
                                            $discount->update(['usage_limit' => $newUsageLimit]);
                                        }
                                    }
                                }
                            }),

                    ])->columnSpan([
                        'md' => 2,
                        'lg' => 2,
                    ])->columns(2),

                Section::make()
                    ->schema([
                        Group::make()
                            ->schema([
                                TextInput::make('booking_reference_no')
                                    ->label('Booking Reference Number')
                                    ->default(fn() => (new Reservation())->generateBookingReference())
                                    ->readOnly(),
                                TextInput::make('booking_fee')
                                    ->numeric()
                                    ->prefix('₱')
                                    ->step(0.01)
                                    ->required()
                                    ->readOnly(),
                                Select::make('booking_status')
                                    ->options(function ($operation) {
                                        if ($operation === 'view') {
                                            return [
                                                'active' => 'Active',
                                                'cancelled' => 'Cancelled',
                                                'on_hold' => 'On Hold',
                                            ];
                                        } else {
                                            return [
                                                'active' => 'Active',
                                                'cancelled' => 'Cancelled',
                                            ];
                                        }
                                    })
                                    ->default(function ($operation) {
                                        if ($operation === 'create') {
                                            return 'on_hold';
                                        }
                                    })
                                    ->required()
                                    ->hidden(function ($operation, $record) {
                                        if ($operation === 'create') {
                                            return true;
                                        } elseif ($operation === 'edit' && $record->booking_status === 'on_hold') {
                                            return true;
                                        }
                                        return false;
                                    }),
                                DateTimePicker::make('on_hold_expiration_date')
                                    ->date()
                                    ->disabled()
                                    ->hidden(function ($operation, $record) {
                                        if ($operation === 'create' || $operation === 'edit') {
                                            return true;
                                        } elseif ($record->booking_status === 'active' || $record->booking_status === 'cancelled') {
                                            return true;
                                        }
                                    }),
                            ]),
                    ])->columnSpan([
                        'md' => 1,
                        'lg' => 1,
                    ]),
            ])->columns([
                'md' => 3,
                'lg' => 3,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_reference_no')
                    ->searchable(),
                TextColumn::make('accommodation_id')
                    ->label('Accommodation')
                    ->searchable()
                    ->formatStateUsing(function ($record) {
                        return $record->accommodation->room_name;
                    }),
                TextColumn::make('guest_id')
                    ->label('Guest Name')
                    ->formatStateUsing(function ($record) {
                        return $record->guest->first_name . ' ' . $record->guest->last_name;
                    }),
                TextColumn::make('check_in_date')
                    ->date()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('check_out_date')
                    ->date()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('dateFilter')
                    ->options([
                        'present' => 'Present Reservations',
                        'past' => 'Past Reservations',
                        'today' => 'Today',
                        'thisWeek' => 'This Week',
                        'lastWeek' => 'Last Week',
                        'thisMonth' => 'This Month',
                        'lastMonth' => 'Last Month',
                    ])
                    ->default('thisMonth')
                    ->query(function (Builder $query, $data) {
                        if (isset($data['value'])) {
                            switch ($data['value']) {
                                case 'today':
                                    $query->whereDate('check_in_date', '=', Carbon::today())
                                        ->orWhere(function ($query) {
                                            $query->whereDate('check_in_date', '<', Carbon::today())
                                                ->whereDate('check_out_date', '>', Carbon::today());
                                        });
                                    break;

                                case 'present':
                                    $query->whereDate('check_in_date', '>=', Carbon::today())
                                        ->orWhere(function ($query) {
                                            $query->whereDate('check_in_date', '<', Carbon::today())
                                                ->where('check_out_date', '>', Carbon::today());
                                        });
                                    break;

                                case 'past':
                                    $query->whereDate('check_out_date', '<', Carbon::today());
                                    break;

                                case 'thisWeek':
                                    $query->where(function ($query) {
                                        $query->whereBetween('check_in_date', [
                                            Carbon::now()->startOfWeek(),
                                            Carbon::now()->endOfWeek(),
                                        ])
                                            ->orWhereBetween('check_out_date', [
                                                Carbon::now()->startOfWeek()->addDay(),
                                                Carbon::now()->endOfWeek(),
                                            ]);
                                    });
                                    break;

                                case 'lastWeek':
                                    $query->where(function ($query) {
                                        $query->whereBetween('check_in_date', [
                                            Carbon::now()->subWeek()->startOfWeek(),
                                            Carbon::now()->subWeek()->endOfWeek(),
                                        ])
                                            ->orWhereBetween('check_out_date', [
                                                Carbon::now()->subWeek()->startOfWeek()->addDay(),
                                                Carbon::now()->subWeek()->endOfWeek(),
                                            ]);
                                    });
                                    break;

                                case 'thisMonth':
                                    $query->where(function ($query) {
                                        $query->whereMonth('check_in_date', '=', Carbon::now()->month)
                                            ->whereYear('check_in_date', '=', Carbon::now()->year)
                                            ->orWhere(function ($query) {
                                                $query->whereMonth('check_out_date', '=', Carbon::now()->month)
                                                    ->whereYear('check_out_date', '=', Carbon::now()->year)
                                                    ->whereDay('check_out_date', '>', 1);
                                            });
                                    });
                                    break;

                                case 'lastMonth':
                                    $query->whereMonth('check_in_date', '=', Carbon::now()->subMonth()->month)
                                        ->whereYear('check_in_date', '=', Carbon::now()->subMonth()->year)
                                        ->orWhere(function ($query) {
                                            $query->whereMonth('check_out_date', '=', Carbon::now()->subMonth()->month)
                                                ->whereYear('check_out_date', '=', Carbon::now()->subMonth()->year)
                                                ->whereDay('check_out_date', '>', 1);
                                        });
                                    break;
                            }
                        }

                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->visible(function ($record) {
                            return !$record->trashed();
                        }),
                    Tables\Actions\EditAction::make()
                        ->visible(function ($record) {
                            return !$record->trashed();
                        })
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make()
                        ->visible(function ($record) {
                            return auth()->check() && auth()->user()->role === 1 && $record->trashed();
                        })
                        ->color('success'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make()
                        ->visible(function () {
                            return auth()->check() && auth()->user()->role === 1;
                        }),
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
}

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
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

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
            ->schema([
                Section::make()
                    ->schema([
                        DatePicker::make('check_in_date_picker')
                            ->required()
                            ->date()
                            ->minDate(function ($operation, $record) {
                                if ($operation === 'create') {
                                    return today();
                                } elseif ($operation === 'edit' && $record) {
                                    return $record->check_in_date;
                                }
                                return null;
                            })
                            ->afterStateUpdated(function ($set) {
                                $set('check_in_date', null);
                                $set('check_out_date', null);
                                $set('check_out_date_picker', null);
                                $set('booking_fee', null);
                            })
                            ->live(debounce: 100)
                            ->hidden(fn($operation) => $operation === 'view')
                            ->native(false),

                        DatePicker::make('check_out_date_picker')
                            ->required()
                            ->date()
                            ->minDate(function ($get) {
                                $checkInDate = $get('check_in_date_picker');
                                if ($checkInDate) {
                                    return Carbon::parse($checkInDate)->addDay();
                                } else {
                                    return today();
                                }
                            })
                            ->afterStateUpdated(function ($set) {
                                $set('check_in_date', null);
                                $set('check_out_date', null);
                                $set('booking_fee', null);
                                $set('availableDates', null);
                            })
                            ->disabled(function ($get) {
                                if (!$get('check_in_date_picker')) {
                                    return true;
                                }
                            })
                            ->hidden(fn($operation) => $operation === 'view')
                            ->live(debounce: 100)
                            ->native(false),

                        Select::make('accommodation_id')
                            ->label('Room Name')
                            ->relationship('accommodation', 'room_name')
                            ->live(debounce: 100)
                            ->required()
                            ->hidden(function ($get, $operation) {
                                if ($operation === 'view') {
                                    return false;
                                } else {
                                    if (!$get('check_in_date_picker') || !$get('check_out_date_picker')) {
                                        return true;
                                    }
                                }
                            }),

                        Select::make('availableDates')
                            ->options(function ($get, $record) {

                                $checkInDate = $get('check_in_date_picker');
                                $checkOutDate = $get('check_out_date_picker');
                                $accommodationId = $get('accommodation_id');

                                if ($checkInDate && $checkOutDate) {

                                    $bookings = Reservation::where('accommodation_id', $accommodationId)
                                        ->where(function ($query) use ($checkInDate, $checkOutDate) {
                                            $query->whereBetween('check_in_date', [$checkInDate, $checkOutDate])
                                                ->orWhereBetween('check_out_date', [$checkInDate, $checkOutDate])
                                                ->orWhere(function ($query) use ($checkInDate, $checkOutDate) {
                                                    $query->where('check_in_date', '>', $checkInDate)
                                                        ->where('check_out_date', '<', $checkOutDate);
                                                });
                                        })
                                        ->orderBy('check_in_date', 'asc')
                                        ->get();
                                    $availableAccommodation = [];

                                    if ($bookings->isEmpty()) {
                                        $availableAccommodation[Carbon::parse($checkInDate) . '/' . Carbon::parse($checkOutDate)] = Carbon::parse($checkInDate)->format('M d, Y') . ' - ' . Carbon::parse($checkOutDate)->format('M d, Y');
                                    }

                                    $nextCheckIn = Carbon::parse($checkInDate);

                                    foreach ($bookings as $key => $book) {
                                        // if editing, make the current reservation available
                                        if ($record) {
                                            if ($record->id === $book->id) {
                                                if (!isset($bookings[$key + 1])) {
                                                    $availableAccommodation[Carbon::parse($nextCheckIn) . '/' . Carbon::parse($checkOutDate)] = Carbon::parse($nextCheckIn)->format('M d, Y') . ' - ' . Carbon::parse($checkOutDate)->format('M d, Y');
                                                } else {
                                                    if (!$nextCheckIn->eq($bookings[$key + 1]->check_in_date)) {
                                                        $availableAccommodation[Carbon::parse($nextCheckIn) . '/' . Carbon::parse($bookings[$key + 1]->check_in_date)] = Carbon::parse($nextCheckIn)->format('M d, Y') . ' - ' . Carbon::parse($bookings[$key + 1]->check_in_date)->format('M d, Y');
                                                    }
                                                    $nextCheckIn = Carbon::parse($bookings[$key + 1]->check_out_date)->format('M d, Y');

                                                    continue;
                                                }
                                            }
                                        }

                                        $beforeBookedCheckIn = Carbon::parse($nextCheckIn)->lt(Carbon::parse($book->check_in_date));
                                        $equalsToBooked = Carbon::parse($nextCheckIn)->eq(Carbon::parse($book->check_in_date));

                                        if ($beforeBookedCheckIn && !$equalsToBooked) {
                                            $availableAccommodation[Carbon::parse($nextCheckIn) . '/' . Carbon::parse($book->check_in_date)] = Carbon::parse($nextCheckIn)->format('M d, Y') . ' - ' . Carbon::parse($book->check_in_date)->format('M d, Y');
                                        }

                                        $nextCheckIn = Carbon::parse($book->check_out_date)->format('M d, Y');

                                        if (!isset($bookings[$key + 1]) && Carbon::parse($nextCheckIn)->lt(Carbon::parse($checkOutDate))) {
                                            $availableAccommodation[Carbon::parse($nextCheckIn) . '/' . Carbon::parse($checkOutDate)] = Carbon::parse($nextCheckIn)->format('M d, Y') . ' - ' . Carbon::parse($checkOutDate)->format('M d, Y');
                                        }
                                    }

                                    return $availableAccommodation;
                                }

                                return null;
                            })
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if ($state) {
                                    $availableDates = explode('/', $state);
                                    $checkInDate = Carbon::parse($availableDates[0]);
                                    $checkOutDate = Carbon::parse($availableDates[1]);
                                    $accommodationId = $get('accommodation_id');
                                    $accommodation = Accommodation::find($accommodationId);

                                    $set('check_in_date', Carbon::parse($checkInDate)->format('M d, Y'));
                                    $set('check_out_date', Carbon::parse($checkOutDate)->format('M d, Y'));

                                    $daysBooked = $checkInDate->diffInDays($checkOutDate);
                                    $bookingFee = $daysBooked * $accommodation->booking_fee;

                                    $set('booking_fee', $bookingFee);
                                } else {
                                    $set('check_in_date', null);
                                    $set('check_out_date', null);
                                    $set('booking_fee', null);
                                }
                            })
                            ->hidden(function ($get, $operation) {
                                if ($operation === 'view') {
                                    return true;
                                } else {
                                    if (!$get('accommodation_id') || !$get('check_in_date_picker') || !$get('check_out_date_picker')) {
                                        return true;
                                    }
                                }
                            })
                            ->live(debounce: 100),

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
                            ->required()
                            ->hidden(function ($get, $operation) {
                                if ($operation === 'view') {
                                    return false;
                                } else {
                                    if (!$get('availableDates')) {
                                        return true;
                                    }
                                }
                            })
                            ->disabled(fn($operation) => $operation === 'edit'),
                    ])->columns(2),
                Section::make()
                    ->schema([
                        TextInput::make('booking_reference_no')
                            ->label('Booking Reference Number')
                            ->default(fn() => (new Reservation())->generateBookingReference())
                            ->readOnly(),
                        TextInput::make('booking_fee')
                            ->numeric()
                            ->prefix('₱')
                            ->step(0.01)
                            ->readOnly()
                            ->required(),
                        TextInput::make('check_in_date')
                            ->formatStateUsing(function ($record) {
                                if ($record) {
                                    return Carbon::parse($record->check_in_date)->format('M d, Y');
                                }
                            })
                            ->readOnly(),
                        TextInput::make('check_out_date')
                            ->formatStateUsing(function ($record) {
                                if ($record) {
                                    return Carbon::parse($record->check_out_date)->format('M d, Y');
                                }
                            })
                            ->readOnly(),

                        TextInput::make('on_hold_expiration_date')
                            ->disabled()
                            ->hidden(function ($operation, $record) {
                                if ($operation !== 'view') {
                                    return true;
                                } elseif ($record->booking_status === 'active' || $record->booking_status === 'cancelled') {
                                    return true;
                                }
                            })
                            ->formatStateUsing(function ($record) {
                                if ($record) {
                                    return Carbon::parse($record->on_hold_expiration_date)->format('M d, Y H:i a');
                                }
                            }),

                        TextInput::make('booking_status')
                            ->readOnly()
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
                                        case 'expired':
                                            return 'Expired';
                                            break;
                                    }
                                }
                            })
                            ->hidden(fn($operation) => $operation !== 'view'),
                    ])->columns(2),
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
                SelectFilter::make('booking_status')
                    ->options([
                        'on_hold' => 'On Hold',
                        'active' => 'Active',
                        'cancelled' => 'Cancelled',
                        'expired' => 'Expired',
                    ]),
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
                                    // $query->whereDate('check_in_date', '=', Carbon::today())
                                    //     ->orWhere(function ($query) {
                                    //         $query->whereDate('check_in_date', '<', Carbon::today())
                                    //             ->whereDate('check_out_date', '>', Carbon::today());
                                    //     });

                                    $query->where(function ($query) {
                                        $query->where('booking_status', 'on_hold')
                                            ->orWhere('booking_status', 'active');
                                    })
                                        ->where(function ($query) {
                                            $query->whereDate('check_in_date', '=', Carbon::today())
                                                ->orWhere(function ($query) {
                                                    $query->whereDate('check_in_date', '<', Carbon::today())
                                                        ->whereDate('check_out_date', '>', Carbon::today());
                                                });
                                        });

                                    break;

                                case 'present':
                                    // $query->whereDate('check_in_date', '>=', Carbon::today())
                                    //     ->orWhere(function ($query) {
                                    //         $query->whereDate('check_in_date', '<', Carbon::today())
                                    //             ->where('check_out_date', '>', Carbon::today());
                                    //     });

                                    $query->where(function ($query) {
                                        $query->where('booking_status', 'on_hold')
                                            ->orWhere('booking_status', 'active');
                                    })
                                        ->where(function ($query) {
                                            $query->whereDate('check_in_date', '>=', Carbon::today())
                                                ->orWhere(function ($query) {
                                                    $query->whereDate('check_in_date', '<', Carbon::today())
                                                        ->where('check_out_date', '>', Carbon::today());
                                                });
                                        });

                                    break;

                                case 'past':
                                    $query->where(function ($query) {
                                        $query->where('booking_status', 'on_hold')
                                            ->orWhere('booking_status', 'active');
                                    })
                                        ->whereDate('check_out_date', '<', Carbon::today());
                                    break;

                                case 'thisWeek':
                                    // $query->where(function ($query) {
                                    //     $query->whereBetween('check_in_date', [
                                    //         Carbon::now()->startOfWeek(),
                                    //         Carbon::now()->endOfWeek(),
                                    //     ])
                                    //         ->orWhereBetween('check_out_date', [
                                    //             Carbon::now()->startOfWeek()->addDay(),
                                    //             Carbon::now()->endOfWeek(),
                                    //         ]);
                                    // });

                                    $query->where(function ($query) {
                                        $query->where('booking_status', 'on_hold')
                                            ->orWhere('booking_status', 'active');
                                    })
                                        ->where(function ($query) {
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
                                    // $query->where(function ($query) {
                                    //     $query->whereBetween('check_in_date', [
                                    //         Carbon::now()->subWeek()->startOfWeek(),
                                    //         Carbon::now()->subWeek()->endOfWeek(),
                                    //     ])
                                    //         ->orWhereBetween('check_out_date', [
                                    //             Carbon::now()->subWeek()->startOfWeek()->addDay(),
                                    //             Carbon::now()->subWeek()->endOfWeek(),
                                    //         ]);
                                    // });

                                    $query->where(function ($query) {
                                        $query->where('booking_status', 'on_hold')
                                            ->orWhere('booking_status', 'active');
                                    })
                                        ->where(function ($query) {
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
                                    // $query->where(function ($query) {
                                    //     $query->whereMonth('check_in_date', '=', Carbon::now()->month)
                                    //         ->whereYear('check_in_date', '=', Carbon::now()->year)
                                    //         ->orWhere(function ($query) {
                                    //             $query->whereMonth('check_out_date', '=', Carbon::now()->month)
                                    //                 ->whereYear('check_out_date', '=', Carbon::now()->year)
                                    //                 ->whereDay('check_out_date', '>', 1);
                                    //         });
                                    // });

                                    $query->where(function ($query) {
                                        $query->where('booking_status', 'on_hold')
                                            ->orWhere('booking_status', 'active');
                                    })
                                        ->where(function ($query) {
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
                                    // $query->whereMonth('check_in_date', '=', Carbon::now()->subMonth()->month)
                                    //     ->whereYear('check_in_date', '=', Carbon::now()->subMonth()->year)
                                    //     ->orWhere(function ($query) {
                                    //         $query->whereMonth('check_out_date', '=', Carbon::now()->subMonth()->month)
                                    //             ->whereYear('check_out_date', '=', Carbon::now()->subMonth()->year)
                                    //             ->whereDay('check_out_date', '>', 1);
                                    //     });

                                    $query->where(function ($query) {
                                        $query->where('booking_status', 'on_hold')
                                            ->orWhere('booking_status', 'active');
                                    })
                                        ->where(function ($query) {
                                            $query->whereMonth('check_in_date', '=', Carbon::now()->subMonth()->month)
                                                ->whereYear('check_in_date', '=', Carbon::now()->subMonth()->year)
                                                ->orWhere(function ($query) {
                                                    $query->whereMonth('check_out_date', '=', Carbon::now()->subMonth()->month)
                                                        ->whereYear('check_out_date', '=', Carbon::now()->subMonth()->year)
                                                        ->whereDay('check_out_date', '>', 1);
                                                });
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

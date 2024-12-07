<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccommodationPromoResource\Pages;
use App\Filament\Resources\AccommodationPromoResource\RelationManagers;
use App\Models\AccommodationPromo;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;
use Closure;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;

class AccommodationPromoResource extends Resource
{
    protected static ?string $model = AccommodationPromo::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('accommodation_id')
                                    ->relationship(name: 'accommodation', titleAttribute: 'room_name')
                                    ->required()
                                    // ->searchable()
                                    ->disabled(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord)
                                    ->live()
                                    ->afterStateUpdated(function ($set, $get) {
                                        static::updateDiscountedPrice($set, $get);
                                    })
                                    ->columnSpan('full')
                                    ->afterStateUpdated(function ($set) {
                                        $set('promo_start_date', null);
                                        $set('promo_end_date', null);
                                    }),

                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('value')
                                            ->required()
                                            ->live(debounce: 500)
                                            ->numeric()
                                            ->label('Percentage Value')
                                            ->suffixIcon('heroicon-o-percent-badge')
                                            ->suffixIconColor('primary')
                                            ->afterStateUpdated(function ($set, $get) {
                                                static::updateDiscountedPrice($set, $get);
                                            }),
                                        Forms\Components\TextInput::make('discounted_price')
                                            ->label('Discounted Price')
                                            ->required()
                                            ->readOnly()
                                            ->live()
                                            ->prefix('₱')
                                            ->numeric()
                                            ->afterStateUpdated(function ($set, $get) {
                                                $set('discounted_price', $get('discounted_price'));
                                            }),

                                        // Forms\Components\DatePicker::make('promo_start_date')
                                        //     ->required()
                                        //     ->date()
                                        //     ->minDate(today())
                                        //     ->suffixIcon('heroicon-o-calendar-days')
                                        //     ->suffixIconColor('success')
                                        //     ->reactive()
                                        //     ->native(false)
                                        //     ->disabledDates(function ($get) {
                                        //         $accommodationId = $get('accommodation_id');
                                        //         $existingPromos = AccommodationPromo::where('deleted_at', null)
                                        //             ->where('accommodation_id', $accommodationId)->get();

                                        //         $reservedDatesFormatted = $existingPromos->flatMap(function ($promo) {
                                        //             $promoStartDate = Carbon::parse($promo->promo_start_date);
                                        //             $promoEndDate = Carbon::parse($promo->promo_end_date);

                                        //             return collect(range(0, $promoEndDate->diffInDays($promoStartDate)))
                                        //                 ->map(fn($days) => $promoStartDate->copy()->addDays($days)->toDateString());
                                        //         });

                                        //         return $reservedDatesFormatted->toArray();
                                        //     })
                                        //     ->afterStateUpdated(function ($set, $get) {
                                        //         self::updateStatus($get, $set);
                                        //         $set('promo_end_date', null);
                                        //     }),

                                        // Forms\Components\DatePicker::make('promo_end_date')
                                        //     ->required()
                                        //     ->date()
                                        //     ->reactive()
                                        //     ->suffixIcon('heroicon-o-calendar-days')
                                        //     ->suffixIconColor('danger')
                                        //     ->disabled(fn($get) => !$get('promo_start_date'))
                                        //     ->minDate(function ($get) {
                                        //         $promo_start_date = $get('promo_start_date');
                                        //         return $promo_start_date ? Carbon::parse($promo_start_date)->addDay() : today()->addDay();
                                        //     })
                                        //     ->native(false)
                                        //     ->disabledDates(function ($get) {
                                        //         $disabledDates = [];

                                        //         $startDate = $get('promo_start_date');
                                        //         if ($startDate) {
                                        //             $accommodationId = $get('accommdation_id');
                                        //             $existingPromos = AccommodationPromo::where('deleted_at', null)
                                        //                 ->where('accommodation_id', $accommodationId)->get();

                                        //             $reservedDatesFormatted = $existingPromos->flatMap(function ($promo) {
                                        //                 $promoStartDate = Carbon::parse($promo->promo_start_date);
                                        //                 $promoEndDate = Carbon::parse($promo->promo_end_date);

                                        //                 return collect(range(0, $promoEndDate->diffInDays($promoStartDate)))
                                        //                     ->map(fn($days) => $promoStartDate->copy()->addDays($days)->toDateString());
                                        //             });

                                        //             $disabledDates = $reservedDatesFormatted->toArray();
                                        //         }

                                        //         return $disabledDates;
                                        //     })
                                        //     ->afterStateUpdated(function ($set, $get) {
                                        //         self::updateStatus($get, $set);
                                        //     })->visible(fn($get) => $get('promo_start_date')),

                                        Forms\Components\DatePicker::make('promo_start_date')
                                            ->required()
                                            ->label('Promotion Start')
                                            ->date()
                                            ->minDate(today())
                                            ->suffixIcon('heroicon-o-calendar-days')
                                            ->suffixIconColor('success')
                                            ->reactive()
                                            ->native(false)
                                            ->disabledDates(function ($get, $set, $state) {
                                                $accommodationId = $get('accommodation_id');
                                                $promoId = $get('id');

                                                $existingPromos = AccommodationPromo::whereNull('deleted_at')
                                                    ->where('accommodation_id', $accommodationId)
                                                    ->where('status', '!=', 'expired')
                                                    ->when($promoId, fn($query) => $query->where('id', '!=', $promoId))
                                                    ->get();

                                                $reservedDatesFormatted = $existingPromos->flatMap(function ($promo) {
                                                    $promoStartDate = Carbon::parse($promo->promo_start_date);
                                                    $promoEndDate = Carbon::parse($promo->promo_end_date);

                                                    return collect(range(0, $promoEndDate->diffInDays($promoStartDate)))
                                                        ->map(fn($days) => $promoStartDate->copy()->addDays($days)->toDateString());
                                                });

                                                return $reservedDatesFormatted->toArray();
                                            })
                                            ->afterStateUpdated(function ($set, $get) {
                                                self::updateStatus($get, $set);
                                                $set('promo_end_date', null);
                                            }),

                                        Forms\Components\DatePicker::make('promo_end_date')
                                            ->required()
                                            ->label('Promotion End')
                                            ->date()
                                            ->reactive()
                                            ->suffixIcon('heroicon-o-calendar-days')
                                            ->suffixIconColor('danger')
                                            ->disabled(fn($get) => !$get('promo_start_date'))
                                            ->minDate(function ($get) {
                                                $promo_start_date = $get('promo_start_date');
                                                $currentEndDate = $get('promo_end_date');

                                                return ($currentEndDate && Carbon::now()->toDateString() == Carbon::parse($currentEndDate)->toDateString())
                                                    ? Carbon::parse($promo_start_date)->addDay()->startOfDay()
                                                    : Carbon::parse($promo_start_date)->addDay()->startOfDay();
                                            })
                                            ->native(false)
                                            ->disabledDates(
                                                function ($get) {
                                                    $startDate = $get('promo_start_date');
                                                    $accommodationId = $get('accommodation_id');
                                                    $promoId = $get('id');

                                                    if (!$startDate || !$accommodationId) {
                                                        return [];
                                                    }

                                                    $existingPromos = AccommodationPromo::whereNull('deleted_at')
                                                        ->where('accommodation_id', $accommodationId)
                                                        ->where('status', '!=', 'expired')
                                                        ->when($promoId, fn($query) => $query->where('id', '!=', $promoId))
                                                        ->get();

                                                    return $existingPromos->flatMap(function ($promo) {
                                                        $promoStartDate = Carbon::parse($promo->promo_start_date);
                                                        $promoEndDate = Carbon::parse($promo->promo_end_date);
                                                        return collect(range(0, $promoEndDate->diffInDays($promoStartDate)))
                                                            ->map(fn($days) => $promoStartDate->copy()->addDays($days)->toDateString());
                                                    })->toArray();
                                                }
                                            )
                                            ->afterStateUpdated(function ($set, $get) {
                                                self::updateStatus($get, $set);
                                            })
                                            ->rule(function (\Filament\Forms\Get $get, $state) {
                                                return [
                                                    function (string $attribute, $value, \Closure $fail) use ($get, $state) {
                                                        $promoStartDate = $get('promo_start_date');
                                                        $accommodationId = $get('accommodation_id');
                                                        $state = Carbon::parse($state)->format('M d, Y');

                                                        if (!$promoStartDate || !$accommodationId) {
                                                            return;
                                                        }

                                                        $overlappingPromos = AccommodationPromo::where('accommodation_id', $accommodationId)
                                                            ->where(function ($query) use ($promoStartDate, $value) {
                                                                $query->whereBetween('promo_start_date', [$promoStartDate, $value])
                                                                    ->orWhereBetween('promo_end_date', [$promoStartDate, $value])
                                                                    ->orWhere(function ($query) use ($promoStartDate, $value) {
                                                                        $query->where('promo_start_date', '<=', $promoStartDate)
                                                                            ->where('promo_end_date', '>=', $value);
                                                                    });
                                                            })
                                                            ->exists();

                                                        if ($overlappingPromos) {
                                                            $fail("The selected promo end date {$state} overlaps with an existing promotion.");
                                                        }
                                                    },
                                                ];
                                            })
                                            ->visible(fn($get) => $get('promo_start_date')),

                                        Forms\Components\Hidden::make('status')
                                            ->reactive()
                                            ->afterStateHydrated(function ($set, $get) {
                                                self::updateStatus($get, $set);
                                            })
                                            ->afterStateUpdated(function ($set, $get) {
                                                self::updateStatus($get, $set);
                                            }),
                                    ])
                                    ->visible(fn($get) => $get('accommodation_id'))
                                    ->columnSpan([
                                        'md' => 2,
                                        'lg' => 2,
                                    ])->columns(2),


                            ])
                    ])
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('accommodation.room_name')
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Percentage Value')
                    ->searchable()
                    ->badge()
                    ->color('gray')
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('discounted_price')
                    ->label('Discounted Price')
                    ->prefix('₱')
                    ->numeric()
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('promotion_date')
                    ->label('Promotion Date')
                    ->formatStateUsing(fn($state) => $state ? $state : 'N/A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Promo Status')
                    ->badge()
                    ->icons([
                        'incoming' => 'heroicon-o-calendar',
                        'active' => 'heroicon-o-check-circle',
                        'expired' => 'heroicon-o-x-circle',
                    ])
                    ->color(fn(string $state): string => match ($state) {
                        'incoming' => 'warning',
                        'active' => 'success',
                        'expired' => 'danger',
                    })

                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),

                SelectFilter::make('status')
                    ->multiple()
                    ->label('Promo Status')
                    ->options([
                        'active' => 'Active',
                        'incoming' => 'Incoming',
                        'expired' => 'Expired',
                    ])
                    ->placeholder('All Status')
                    ->searchable()
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->visible(fn($record) => !$record->trashed()),
                    Tables\Actions\EditAction::make()
                        ->color('warning')
                        ->visible(fn($record) => !$record->trashed()),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make()
                        ->color('success'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort(function ($query) {
                $query->orderByRaw("FIELD(status, 'active', 'incoming', 'expired')");
            });
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccommodationPromos::route('/'),
            'create' => Pages\CreateAccommodationPromo::route('/create'),
            'view' => Pages\ViewAccommodationPromo::route('/{record}'),
            'edit' => Pages\EditAccommodationPromo::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->role == 1;
    }

    private static function updateDiscountedPrice($set, $get)
    {
        $accommodationId = $get('accommodation_id');
        $value = (float) $get('value');

        $discountedPrice = AccommodationPromo::calculateDiscountedPrice($value, $accommodationId);

        $set('discounted_price', $discountedPrice);
    }

    protected static function updateStatus($get, $set)
    {
        $now = Carbon::now();
        $promoStartDate = $get('promo_start_date');
        $promoEndDate = $get('promo_end_date');

        if ($promoStartDate && $promoEndDate) {
            $startDate = Carbon::parse($promoStartDate);
            $endDate = Carbon::parse($promoEndDate);

            if ($now->between($startDate, $endDate)) {
                $set('status', 'active');
            } elseif ($startDate > $now) {
                $set('status', 'incoming');
            } elseif ($now->isAfter($endDate)) {
                $set('status', 'expired');
            }
        } else {
            $set('status', 'unknown');
        }
    }
}

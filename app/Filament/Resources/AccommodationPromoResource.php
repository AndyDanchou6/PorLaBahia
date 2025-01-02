<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccommodationPromoResource\Pages;
use App\Filament\Resources\AccommodationPromoResource\RelationManagers;
use App\Models\Accommodation;
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
use Filament\Support\RawJs;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Log;

class AccommodationPromoResource extends Resource
{
    protected static ?string $model = AccommodationPromo::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('accommodation_id')
                                    ->relationship(name: 'accommodation', titleAttribute: 'room_name')
                                    ->required()
                                    ->disabled(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord)
                                    ->live()
                                    ->afterStateUpdated(function ($set, $get) {
                                        static::updateDiscountedPrice($set, $get);
                                    })
                                    ->afterStateUpdated(function ($set) {
                                        $set('promo_start_date', null);
                                        $set('promo_end_date', null);
                                    }),
                                Forms\Components\TextInput::make('value')
                                    ->required()
                                    ->live(debounce: 500)
                                    ->numeric()
                                    ->label('Percentage')
                                    ->suffixIcon('heroicon-o-percent-badge')
                                    ->suffixIconColor('primary')
                                    ->afterStateUpdated(function ($set, $get) {
                                        static::updateDiscountedPrice($set, $get);
                                    }),

                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('weekday_promo_price')
                                            ->label('Weekday Promo Price')
                                            ->required()
                                            ->readOnly()
                                            ->prefix('₱')
                                            ->numeric()
                                            ->hint(function ($get) {
                                                $id = $get('accommodation_id');
                                                $accommodationId = Accommodation::find($id);
                                                $weekday_price = $accommodationId->weekday_price;

                                                return "Original Price: ₱{$weekday_price}";
                                            })
                                            ->hintColor('success')
                                            ->reactive(),

                                        Forms\Components\TextInput::make('weekend_promo_price')
                                            ->label('Weekend Promo Price')
                                            ->required()
                                            ->readOnly()
                                            ->prefix('₱')
                                            ->numeric()
                                            ->hint(function ($get) {
                                                $id = $get('accommodation_id');
                                                $accommodationId = Accommodation::find($id);
                                                $weekend_price = $accommodationId->weekend_price;

                                                return "Original Price: ₱{$weekend_price}";
                                            })
                                            ->hintColor('success')
                                            ->reactive(),

                                        Forms\Components\DatePicker::make('promo_start_date')
                                            ->required()
                                            ->label('Promotion Start')
                                            ->date()
                                            ->visible(fn($get) => $get('value'))
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
                                                        $promoId = $get('id');
                                                        $state = Carbon::parse($state)->format('M d, Y');

                                                        if (!$promoStartDate || !$accommodationId) {
                                                            return;
                                                        }

                                                        $query = AccommodationPromo::where('accommodation_id', $accommodationId)
                                                            ->where(function ($query) use ($promoStartDate, $value) {
                                                                $query->whereBetween('promo_start_date', [$promoStartDate, $value])
                                                                    ->orWhereBetween('promo_end_date', [$promoStartDate, $value])
                                                                    ->orWhere(function ($query) use ($promoStartDate, $value) {
                                                                        $query->where('promo_start_date', '<=', $promoStartDate)
                                                                            ->where('promo_end_date', '>=', $value);
                                                                    });
                                                            })
                                                            ->where('status', '!=', 'expired');

                                                        if ($promoId) {
                                                            $query->where('id', '!=', $promoId);
                                                        }

                                                        $overlappingPromos = $query->exists();

                                                        if ($overlappingPromos) {
                                                            $fail("The selected promotion end date, {$state}, conflicts with an existing promotion. Please ensure the dates do not overlap.");
                                                        }
                                                    },
                                                ];
                                            })
                                            ->visible(fn($get) => $get('promo_start_date')),

                                        Forms\Components\Placeholder::make('Warning!')
                                            ->content(fn($get, $state) => self::generatePromoValidationMessage($get, $state))
                                            ->visible(fn($get, $state) => self::generatePromoValidationMessage($get, $state) !== false)
                                            ->hidden(fn($get) => !$get('promo_end_date'))
                                            ->columnSpan('full')
                                            ->extraAttributes([
                                                'style' => 'color: yellow;',
                                            ]),

                                        Forms\Components\FileUpload::make('featured_image_promo')
                                            ->visible(fn($get) => $get('value'))
                                            ->label('Featured Image')
                                            ->image()
                                            ->columnSpan('full'),

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
                    ->label('Percentage')
                    ->searchable()
                    ->badge()
                    ->color('gray')
                    ->suffix('%'),

                Tables\Columns\TextColumn::make('weekday_promo_price')
                    ->label('Weekday Promo Price')
                    ->prefix('₱')
                    ->numeric()
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('weekend_promo_price')
                    ->label('Weekend Promo Price')
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
                        ->visible(function ($record) {
                            if ($record->status == 'expired') {
                                return false;
                            }

                            if ($record->trashed()) {
                                return false;
                            }
                            return true;
                        }),
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
        $id = $get('accommodation_id');
        $accommodationId = Accommodation::find($id);

        if ($accommodationId) {
            $value = (float) $get('value');
            $weekday_price = $accommodationId->weekday_price;
            $weekend_price = $accommodationId->weekend_price;

            $weekday_discount_price = $weekday_price - ($weekday_price * $value / 100);
            $weekend_discount_price = $weekend_price - ($weekend_price * $value / 100);

            $set('weekday_promo_price', max($weekday_discount_price, 0));
            $set('weekend_promo_price', max($weekend_discount_price, 0));
        } else {
            $set('weekday_promo_price', null);
            $set('weekend_promo_price', null);
        }
    }

    private static function generatePromoValidationMessage($get, $state): string|false
    {
        $validationResult = self::validatePromoDates($get, $state);

        foreach ($validationResult as $error) {
            return $error;
        }

        return false;
    }


    private static function validatePromoDates($get, $state): array
    {
        $messages = [];

        $startDate = $get('promo_start_date');
        $endDate = $get('promo_end_date');
        $accommodationId = $get('accommodation_id'); // Updated to use $get

        $promoId = $get('id');

        if (!$startDate || !$accommodationId) {
            return $messages;
        }

        if (!$endDate) {
            return ["Promotion end date is required."];
        }

        $query = AccommodationPromo::where('accommodation_id', $accommodationId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('promo_start_date', [$startDate, $endDate])
                    ->orWhereBetween('promo_end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('promo_start_date', '<=', $startDate)
                            ->where('promo_end_date', '>=', $endDate);
                    });
            })
            ->where('status', '!=', 'expired')
            ->when($promoId, fn($query) => $query->where('id', '!=', $promoId))
            ->orderBy('promo_start_date')
            ->first();

        if ($query) {
            $messages[] = sprintf(
                "The selected promotion end date conflicts with an existing promotion from %s to %s.",
                Carbon::parse($query->promo_start_date)->format('M d, Y'),
                Carbon::parse($query->promo_end_date)->format('M d, Y')
            );
        }

        return $messages;
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

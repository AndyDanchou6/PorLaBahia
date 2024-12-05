<?php

namespace App\Filament\Resources\AccommodationResource\RelationManagers;

use App\Models\Accommodation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\AccommodationPromo;
use Carbon\Carbon;

class AccommodationPromoRelationManager extends RelationManager
{
    protected static string $relationship = 'accommodation_promo';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->reactive()
                    ->numeric()
                    ->suffixIcon('heroicon-o-percent-badge')
                    ->suffixIconColor('primary')
                    ->afterStateUpdated(function ($set, $get) {
                        static::calculateDiscountedPrice($set, $get);
                    }),
                Forms\Components\TextInput::make('discounted_price')
                    ->label('Discounted Price')
                    ->required()
                    ->readOnly()
                    ->reactive()
                    ->prefix('₱')
                    ->numeric()
                    ->afterStateUpdated(function ($set, $get) {
                        $set('discounted_price', $get('discounted_price'));
                    }),

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
                        $accommodationId = $this->getOwnerRecord()->id;
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
                        self::updatePromoStatus($get, $set);
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
                    ->disabledDates(function ($get) {
                        $startDate = $get('promo_start_date');
                        $accommodationId = $this->getOwnerRecord()->id;

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
                    })
                    ->afterStateUpdated(function ($set, $get) {
                        self::updatePromoStatus($get, $set);
                    })
                    ->visible(fn($get) => $get('promo_start_date')),

                Forms\Components\Hidden::make('status')
                    ->reactive()
                    ->afterStateHydrated(function ($set, $get) {
                        self::updatePromoStatus($get, $set);
                    })
                    ->afterStateUpdated(function ($set, $get) {
                        self::updatePromoStatus($get, $set);
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('discount_type')
            ->columns([
                Tables\Columns\TextColumn::make('value')
                    ->label('Percentage Value')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('discounted_price')
                    ->label('Discounted Price')
                    ->badge()
                    ->prefix('₱')
                    ->numeric()
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('promotion_date')
                    ->label('Promotion Date')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'incoming' => 'warning',
                        'active' => 'success',
                        'expired' => 'danger',
                    })
                    ->sortable()
                    ->formatStateUsing(fn($state) => ucwords($state)),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\ActionGroup::make([
                Tables\Actions\EditAction::make()
                    ->color('warning'),
                Tables\Actions\DeleteAction::make(),
                // ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort(function ($query) {
                $query->orderByRaw("FIELD(status, 'active', 'incoming', 'expired')");
            });;
    }


    private function calculateDiscountedPrice($set, $get)
    {
        $accommodation = $this->getOwnerRecord();

        if ($accommodation) {
            $value = (float) $get('value');
            $weekday_price = $accommodation->weekday_price;
            $weekend_price = $accommodation->weekend_price;

            if (Carbon::now()->isWeekday()) {
                $discountPrice = $weekday_price - ($weekday_price * $value / 100);
            } elseif (Carbon::now()->isWeekend()) {
                $discountPrice = $weekend_price - ($weekend_price * $value / 100);
            }

            $set('discounted_price', max($discountPrice, 0));
        } else {
            $set('discounted_price', null);
        }
    }

    protected static function updatePromoStatus($get, $set)
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

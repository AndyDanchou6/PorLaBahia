<?php

namespace App\Filament\Resources\AccommodationResource\RelationManagers;

use App\Models\AccommodationPromo;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AccommodationPromoRelationManager extends RelationManager
{
    protected static string $relationship = 'accommodation_promo';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Fieldset::make('')
                ->schema([
                    Forms\Components\TextInput::make('value')
                        ->label('Percentage')
                        ->required()
                        ->numeric()
                        ->reactive()
                        ->suffixIcon('heroicon-o-percent-badge')
                        ->suffixIconColor('primary')
                        ->afterStateUpdated(fn($set, $get) => $this->calculateDiscountedPrice($set, $get)),

                    Forms\Components\Fieldset::make('Promotion Information')
                        ->visible(fn($get) => $get('value'))
                        ->schema([
                            Forms\Components\TextInput::make('weekday_promo_price')
                                ->label('Weekday Promo Price')
                                ->required()
                                ->readOnly()
                                ->prefix('₱')
                                ->numeric()
                                ->hint(function ($get) {
                                    $accommodationId = $this->getOwnerRecord();

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
                                    $accommodationId = $this->getOwnerRecord();

                                    $weekend_price = $accommodationId->weekend_price;

                                    return "Original Price: ₱{$weekend_price}";
                                })
                                ->hintColor('success')
                                ->reactive(),
                        ]),

                    Forms\Components\Fieldset::make('Promotion Period')
                        ->visible(fn($get) => $get('value'))
                        ->schema([
                            Forms\Components\DatePicker::make('promo_start_date')
                                ->required()
                                ->label('Promotion Start')
                                ->date()
                                ->minDate(today())
                                ->reactive()
                                ->suffixIcon('heroicon-o-calendar-days')
                                ->suffixIconColor('success')
                                ->native(false)
                                ->disabledDates(fn($get) => $this->getReservedDates($get))
                                ->afterStateUpdated(function ($set, $get) {
                                    self::updatePromoStatus($get, $set);
                                    $this->resetEndDate($set, $get);
                                }),

                            Forms\Components\DatePicker::make('promo_end_date')
                                ->required()
                                ->label('Promotion End')
                                ->date()
                                ->reactive()
                                ->hidden(fn($get) => !$get('promo_start_date'))
                                ->minDate(fn($get) => $this->calculateMinEndDate($get))
                                ->suffixIcon('heroicon-o-calendar-days')
                                ->suffixIconColor('danger')
                                ->native(false)
                                ->disabledDates(fn($get) => $this->getReservedDates($get))
                                ->rule(function (\Filament\Forms\Get $get, $state) {
                                    return [
                                        function (string $attribute, $value, \Closure $fail) use ($get, $state) {
                                            $promoStartDate = $get('promo_start_date');
                                            $accommodationId = $this->getOwnerRecord()->id;
                                            $promoId = $get('id');
                                            $state = Carbon::parse($state)->format('M d, Y');

                                            if (
                                                !$promoStartDate || !$accommodationId
                                            ) {
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

                                            return false;
                                        },
                                    ];
                                })
                                ->afterStateUpdated(function ($set, $get) {
                                    self::updatePromoStatus($get, $set);
                                })
                        ]),

                    Forms\Components\Placeholder::make('Warning!')
                        ->content(fn($get, $state) => $this->generatePromoValidationMessage($get, $state))
                        ->visible(fn($get, $state) => $this->generatePromoValidationMessage($get, $state) !== false)
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
                            self::updatePromoStatus($get, $set);
                        })
                        ->afterStateUpdated(function ($set, $get) {
                            self::updatePromoStatus($get, $set);
                        }),
                ])
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('discount_type')
            ->columns([
                Tables\Columns\TextColumn::make('value')
                    ->label('Percentage')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->suffix('%'),

                Tables\Columns\TextColumn::make('weekday_promo_price')
                    ->label('Weekday Promo Price')
                    ->badge()
                    ->prefix('₱')
                    ->numeric()
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('weekend_promo_price')
                    ->label('Weekend Promo Price')
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
                    ->color(fn(string $state) => match ($state) {
                        'incoming' => 'warning',
                        'active' => 'success',
                        'expired' => 'danger',
                    })
                    ->sortable()
                    ->formatStateUsing(fn($state) => ucwords($state)),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('warning')
                    ->visible(fn($record) => $record->status !== 'expired'),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort(fn($query) => $query->orderByRaw("FIELD(status, 'active', 'incoming', 'expired')"));
    }

    private function calculateDiscountedPrice($set, $get)
    {
        $accommodation = $this->getOwnerRecord();

        if ($accommodation) {
            $value = (float) $get('value');
            $weekday_price = $accommodation->weekday_price;
            $weekend_price = $accommodation->weekend_price;

            $weekday_discount_price = $weekday_price - ($weekday_price * $value / 100);
            $weekend_discount_price = $weekend_price - ($weekend_price * $value / 100);

            $set('weekday_promo_price', max($weekday_discount_price, 0));
            $set('weekend_promo_price', max($weekend_discount_price, 0));
        } else {
            $set('weekday_promo_price', null);
            $set('weekend_promo_price', null);
        }
    }

    private function getReservedDates($get): array
    {
        $accommodationId = $this->getOwnerRecord()->id;
        $promoId = $get('id');

        $existingPromos = AccommodationPromo::whereNull('deleted_at')
            ->where('accommodation_id', $accommodationId)
            ->where('status', '!=', 'expired')
            ->when($promoId, fn($query) => $query->where('id', '!=', $promoId))
            ->get();

        return $existingPromos->flatMap(function ($promo) {
            $start = Carbon::parse($promo->promo_start_date);
            $end = Carbon::parse($promo->promo_end_date);
            return collect(range(0, $end->diffInDays($start)))->map(fn($days) => $start->copy()->addDays($days)->toDateString());
        })->toArray();
    }

    private function calculateMinEndDate($get)
    {
        $startDate = $get('promo_start_date');
        return $startDate ? Carbon::parse($startDate)->addDay()->startOfDay() : today();
    }

    private function generatePromoValidationMessage($get, $state): string|false
    {
        $validationResult = $this->validatePromoDates($get, $state);

        foreach ($validationResult as $error) {
            return $error;
        }

        return false;
    }


    private function validatePromoDates($get, $state): array
    {
        $messages = [];

        $startDate = $get('promo_start_date');
        $endDate = $get('promo_end_date');
        $accommodationId = $this->getOwnerRecord()->id;
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

    private function resetEndDate($set, $get)
    {
        $set('promo_end_date', null);
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
            } elseif ($startDate >= $now) {
                $set('status', 'incoming');
            } elseif ($now->isAfter($endDate)) {
                $set('status', 'expired');
            }
        }
    }
}

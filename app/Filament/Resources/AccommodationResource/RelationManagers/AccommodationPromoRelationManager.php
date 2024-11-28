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

class AccommodationPromoRelationManager extends RelationManager
{
    protected static string $relationship = 'accommodation_promo';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('discount_type')
                    ->label('Discount Type')
                    ->options([
                        'fixed' => 'Fixed',
                        'percentage' => 'Percentage',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($set, $get) {
                        static::calculateDiscountedPrice($set, $get);
                    }),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->reactive()
                    ->numeric()
                    ->prefix(fn($get) => $get('discount_type') === 'fixed' ? '₱' : null)
                    ->suffix(fn($get) => $get('discount_type') === 'percentage' ? '%' : null)
                    ->afterStateUpdated(function ($set, $get, $state) {

                        static $previousDiscountType = null;

                        if ($previousDiscountType !== $get('discount_type')) {
                            $set('value', null);
                        }

                        $previousDiscountType = $get('discount_type');

                        if ($get('discount_type') === 'percentage') {
                            $set('value', min($state, 100));
                        } elseif ($get('discount_type') === 'fixed') {
                            $set('value', max($state, 0));
                        }

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
                    ->label('Promo Start Date')
                    ->minDate(now()->toDateString())
                    ->required(),
                Forms\Components\DatePicker::make('promo_end_date')
                    ->label('Promo End Date')
                    ->minDate(now()->toDateString())
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('discount_type')
            ->columns([
                Tables\Columns\TextColumn::make('discount_type')
                    ->formatStateUsing(fn($state) => ucwords($state)),
                Tables\Columns\TextColumn::make('value')
                    ->label('Value')
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        $prefix = $record->discount_type === 'fixed' ? '₱' : '';
                        $suffix = $record->discount_type === 'percentage' ? '%' : '';

                        return $prefix . number_format($state, 2) . $suffix;
                    }),
                Tables\Columns\TextColumn::make('discounted_price')
                    ->label('Discounted Price')
                    ->badge()
                    ->prefix('₱')
                    ->numeric()
                    ->color('gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('promo_end_date')
                    ->label('Promo End Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    private function calculateDiscountedPrice($set, $get)
    {
        $accommodation = $this->getOwnerRecord(); // where to access the relationship's owner record

        if ($accommodation) {
            $value = (float) $get('value');
            $discountType = $get('discount_type');
            $price = $accommodation->price;

            if ($discountType === 'fixed') {
                $discountPrice = $price - $value;
            } elseif ($discountType === 'percentage') {
                $discountPrice = $price - ($price * $value / 100);
            } else {
                $discountPrice = $price;
            }

            $set('discounted_price', max($discountPrice, 0));
        } else {
            $set('discounted_price', null);
        }
    }
}

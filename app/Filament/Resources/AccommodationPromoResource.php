<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccommodationPromoResource\Pages;
use App\Filament\Resources\AccommodationPromoResource\RelationManagers;
use App\Models\AccommodationPromo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccommodationPromoResource extends Resource
{
    protected static ?string $model = AccommodationPromo::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('accommodation_id')
                    ->relationship(name: 'accommodation', titleAttribute: 'room_name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($set, $get) {
                        static::updateDiscountedPrice($set, $get);
                    }),
                Forms\Components\Select::make('discount_type')
                    ->label('Discount Type')
                    ->options([
                        'fixed' => 'Fixed',
                        'percentage' => 'Percentage',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($set, $get) {
                        static::updateDiscountedPrice($set, $get);
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

                        static::updateDiscountedPrice($set, $get);
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('accommodation.room_name')
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_type')
                    ->label('Discount Type')
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->searchable(),
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
                    ->prefix('₱')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('promo_start_date')
                    ->label('Promo Start Date')
                    ->date()
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
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make()
                        ->visible(fn($record) => $record->trashed()),
                    Tables\Actions\RestoreAction::make()
                        ->color('success'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
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
        $discountType = $get('discount_type');

        $discountedPrice = AccommodationPromo::calculateDiscountedPrice($value, $discountType, $accommodationId);

        $set('discounted_price', $discountedPrice);
    }
}

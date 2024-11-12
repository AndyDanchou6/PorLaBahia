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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('accommodation_id')
                    ->relationship(name: 'accommodation', titleAttribute: 'room_name')
                    ->required(),
                Forms\Components\TextInput::make('discount_type')
                    ->label('Discount Type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('discounted_price')
                    ->label('Discounted Price')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('promo_start_date')
                    ->label('Promo Start Date')
                    ->required(),
                Forms\Components\DatePicker::make('promo_end_date')
                    ->label('Promo End Date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('accommodation.room_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_type')
                    ->label('Discount Type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->searchable(),
                Tables\Columns\TextColumn::make('discounted_price')
                    ->label('Discounted Price')
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn(AccommodationPromo $record) => !$record->trashed()),
                Tables\Actions\EditAction::make()
                    ->visible(fn(AccommodationPromo $record) => !$record->trashed())
                    ->color('warning'),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make()
                    ->visible(function (AccommodationPromo $record) {
                        return $record->trashed() && auth()->user()->role == 1;
                    })
                    ->color('success'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
}

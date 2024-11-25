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
                    ->required(),
                Forms\Components\Select::make('discount_type')
                    ->label('Discount Type')
                    ->options([
                        'fixed' => 'Fixed',
                        'percentage' => 'Percentage'
                    ])
                    ->reactive()
                    ->required(),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->reactive()
                    ->suffix(fn($get) => $get('discount_type') == 'fixed' ? '₱' : null)
                    ->prefix(fn($get) => $get('discount_type') == 'percentage' ? '%' : null)
                    // ->afterStateUpdated(function ($set) {})
                    ->numeric(),
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
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
}

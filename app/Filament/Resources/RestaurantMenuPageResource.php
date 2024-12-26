<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestaurantMenuPageResource\Pages;
use App\Filament\Resources\RestaurantMenuPageResource\RelationManagers;
use App\Models\ContentManagementSystem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RestaurantMenuPageResource extends Resource
{
    protected static ?string $model = ContentManagementSystem::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $modelLabel = 'Restaurant Menu page';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Restaurant Menu Page';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('page', 'restaurant_menu'))
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListRestaurantMenuPages::route('/'),
            'create' => Pages\CreateRestaurantMenuPage::route('/create'),
            'edit' => Pages\EditRestaurantMenuPage::route('/{record}/edit'),
        ];
    }
}

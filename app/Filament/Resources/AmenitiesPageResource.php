<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AmenitiesPageResource\Pages;
use App\Filament\Resources\AmenitiesPageResource\RelationManagers;
use App\Models\ContentManagementSystem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AmenitiesPageResource extends Resource
{
    protected static ?string $model = ContentManagementSystem::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $modelLabel = 'Amenities Page';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Amenities Page';

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
            ->modifyQueryUsing(fn(Builder $query) => $query->where('page', 'amenities'))
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
            'index' => Pages\ListAmenitiesPages::route('/'),
            'create' => Pages\CreateAmenitiesPage::route('/create'),
            'edit' => Pages\EditAmenitiesPage::route('/{record}/edit'),
        ];
    }
}

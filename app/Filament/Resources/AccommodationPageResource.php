<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccommodationPageResource\Pages;
use App\Filament\Resources\AccommodationPageResource\RelationManagers;
use App\Models\ContentManagementSystem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccommodationPageResource extends Resource
{
    protected static ?string $model = ContentManagementSystem::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $modelLabel = 'Accommodation Page';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Accommodation Page';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Fieldset::make('Page')
                    ->schema([])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('page', 'accommodation'))
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
            'index' => Pages\ListAccommodationPages::route('/'),
            'create' => Pages\CreateAccommodationPage::route('/create'),
            'edit' => Pages\EditAccommodationPage::route('/{record}/edit'),
        ];
    }
}

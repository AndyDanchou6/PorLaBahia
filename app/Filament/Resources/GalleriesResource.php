<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleriesResource\Pages;
use App\Filament\Resources\GalleriesResource\RelationManagers;
use App\Models\Accommodation;
use App\Models\Amenities;
use App\Models\Galleries;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GalleriesResource extends Resource
{
    protected static ?string $model = Galleries::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('gallery_type')
                    ->options([
                        Amenities::class => 'Amenities',
                        Accommodation::class => 'Accommodation',
                    ])
                    ->required()
                    ->reactive()
                    ->label('Choose Category')
                    ->afterStateUpdated(fn(callable $set) => $set('gallery_id', null)),

                Forms\Components\Select::make('gallery_id')
                    ->label('Select Item')
                    ->required()
                    ->reactive()
                    ->searchable()
                    ->options(function (callable $get) {
                        $category = $get('gallery_type');

                        if ($category === Amenities::class) {
                            return Amenities::pluck('amenity_name', 'id');
                        } elseif ($category === Accommodation::class) {
                            return Accommodation::pluck('room_name', 'id');
                        }

                        return [];
                    }),

                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->required()
                    ->columnSpan(2),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular()
                    ->searchable()
                    ->stacked(),
                Tables\Columns\TextColumn::make('category_name')
                    ->label('Category')
                    ->searchable(),
                Tables\Columns\TextColumn::make('selected_name')
                    ->label('Selected Item')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGalleries::route('/create'),
            'view' => Pages\ViewGalleries::route('/{record}'),
            'edit' => Pages\EditGalleries::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccommodationResource\Pages;
use App\Filament\Resources\AccommodationResource\RelationManagers;
use App\Filament\Resources\AccommodationResource\RelationManagers\GalleriesRelationManager;
use App\Models\Accommodation;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Markdown;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;

class AccommodationResource extends Resource
{
    protected static ?string $model = Accommodation::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('room_name')
                    ->required(),

                MarkdownEditor::make('description')
                    ->nullable(),

                Section::make('')
                    ->schema([
                        TextInput::make('capacity')
                            ->integer()
                            ->required(),
                        TextInput::make('price')
                            ->prefix('₱')
                            ->integer()
                            ->required(),
                        FileUpload::make('main_image')
                            ->required(),
                    ])->columns(2),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('main_image')
                    ->circular(),
                TextColumn::make('room_name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                // TextColumn::make('description')
                //     ->sortable()
                //     ->searchable()
                //     ->toggleable(),
                TextColumn::make('capacity')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('price')
                    ->prefix('₱ ')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
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
            RelationManagers\GalleriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccommodations::route('/'),
            'create' => Pages\CreateAccommodation::route('/create'),
            'edit' => Pages\EditAccommodation::route('/{record}/edit'),
            'view' => Pages\ViewAccommodation::route('/{record}')
        ];
    }
}

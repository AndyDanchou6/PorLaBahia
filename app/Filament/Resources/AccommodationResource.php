<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccommodationResource\Pages;
use App\Filament\Resources\AccommodationResource\RelationManagers;
use App\Filament\Resources\AccommodationResource\RelationManagers\GalleriesRelationManager;
use App\Models\Accommodation;
use Closure;
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
use Filament\Resources\RelationManagers\RelationManager;

class AccommodationResource extends Resource
{
    protected static ?string $model = Accommodation::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        TextInput::make('room_name')
                            ->required(),

                        MarkdownEditor::make('description')
                            ->nullable(),
                    ])->columnSpan(2),

                Group::make()
                    ->schema([
                        TextInput::make('weekday_price')
                            ->prefix('₱')
                            ->integer()
                            ->required(),
                        TextInput::make('weekend_price')
                            ->prefix('₱')
                            ->integer()
                            ->required(),
                        FileUpload::make('main_image')
                            ->required(),

                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('main_image')
                    ->circular(),
                TextColumn::make('room_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('weekday_price')
                    ->prefix('₱')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('weekend_price')
                    ->prefix('₱')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(function ($record) {
                        return !$record->trashed();
                    }),
                Tables\Actions\EditAction::make()
                    ->visible(function ($record) {
                        return !$record->trashed();
                    })
                    ->color('warning'),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make()
                    ->visible(function ($record) {
                        return auth()->check() && auth()->user()->role === 1 && $record->trashed();
                    })
                    ->color('success'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make()
                        ->visible(function () {
                            return auth()->check() && auth()->user()->role === 1;
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AccommodationPromoRelationManager::class,
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

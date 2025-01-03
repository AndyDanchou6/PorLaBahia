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

    protected static ?string $recordTitleAttribute = 'room_name';

    protected static ?string $navigationGroup = 'Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        TextInput::make('room_name')
                            ->required()
                            ->columnSpan([
                                'sm' => 2,
                                'md' => 6,
                            ]),
                        TextInput::make('weekday_price')
                            ->prefix('₱')
                            ->numeric()
                            ->step(0.01)
                            ->required()
                            ->columnSpan([
                                'sm' => 2,
                                'md' => 2,
                            ]),
                        TextInput::make('weekend_price')
                            ->prefix('₱')
                            ->numeric()
                            ->step(0.01)
                            ->required()
                            ->columnSpan([
                                'sm' => 2,
                                'md' => 2,
                            ]),
                        TextInput::make('booking_fee')
                            ->numeric()
                            ->step(0.01)
                            ->prefix('₱')
                            ->required()
                            ->columnSpan([
                                'sm' => 2,
                                'md' => 2,
                            ]),
                        MarkdownEditor::make('description')
                            ->nullable()
                            ->disableToolbarButtons([
                                'blockquote',
                                'strike',
                                'codeBlock',
                                'heading',
                                'attachFiles',
                                'table'
                            ])
                            ->columnSpan([
                                'sm' => 2,
                                'md' => 6,
                            ]),
                    ])->columnSpan(2)->columns([
                        'sm' => 2,
                        'md' => 6,
                    ]),

                Group::make()
                    ->schema([
                        FileUpload::make('main_image')
                            ->required()
                            ->image()
                            ->columnSpan(2),
                        TextInput::make('free_pax')
                            ->numeric()
                            ->required()
                            ->columnSpan(1),
                        TextInput::make('excess_pax_price')
                            ->numeric()
                            ->step(0.01)
                            ->prefix('₱')
                            ->required()
                            ->columnSpan(1),
                    ])->columnSpan(1)->columns(2),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('main_image')
                    ->square(),
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
                TextColumn::make('booking_fee')
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
            RelationManagers\GalleriesRelationManager::class,
            RelationManagers\AccommodationPromoRelationManager::class,
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

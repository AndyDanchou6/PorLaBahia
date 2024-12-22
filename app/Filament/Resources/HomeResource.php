<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeResource\Pages;
use App\Filament\Resources\HomeResource\RelationManagers;
use App\Models\ContentManagementSystem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Infolists\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HomeResource extends Resource
{
    protected static ?string $model = ContentManagementSystem::class;

    protected static ?string $modelLabel = 'Home Page';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                \Filament\Forms\Components\Hidden::make('page')
                    ->default('home'),
                \Filament\Forms\Components\Fieldset::make('Page')
                    ->schema([
                        \Filament\Forms\Components\Select::make('section')
                            ->required()
                            ->label('Section Number')
                            ->options([
                                1 => 'Section 1 - Welcome Section',
                                2 => 'Section 2 - About Section',
                                3 => 'Section 3 - Resort Houses Section',
                                4 => 'Section 4 - Quick Video Section'
                            ])
                            ->reactive()
                            ->columnSpan('full'),

                        \Filament\Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->columnSpan('full')
                            ->hidden(function ($get) {
                                if (!$get('section')) {
                                    return true;
                                } elseif ($get('section') == 4) {
                                    return true;
                                } elseif ($get('section') == 3) {
                                    return true;
                                }

                                return false;
                            }),

                        \Filament\Forms\Components\MarkdownEditor::make('value')
                            ->required()
                            ->label(function ($get) {
                                if ($get('section') == 4) {
                                    return 'Video Url';
                                } else {
                                    return 'Content';
                                }
                            })
                            ->placeholder('Enter the content for this section (e.g. About us information, Resort information, Video URL)')
                            ->columnSpan('full')
                            ->hidden(fn($get) => !$get('section'))
                            ->reactive(),

                        \Filament\Forms\Components\Repeater::make('icons')
                            ->schema([
                                \Filament\Forms\Components\FileUpload::make('image')
                                    ->image()
                                    ->label('Icon Image')
                                    ->placeholder('Must be an icon image (PNG, JPG, SVG)'),
                                \Filament\Forms\Components\TextInput::make('icon_name')
                                    ->label('Name')
                            ])
                            ->grid(2)
                            ->label('Icons (Optional)')
                            ->visible(fn($get) => $get('section') == 2)
                            ->addActionLabel('Add another Icon'),
                    ])->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('section')
                    ->formatStateUsing(function ($record) {
                        if ($record->section == 1) {
                            return 'Welcome Section';
                        } elseif ($record->section == 2) {
                            return 'About Section';
                        } elseif ($record->section == 3) {
                            return 'Resort Houses Section';
                        } elseif ($record->section == 4) {
                            return 'Quick Video Section';
                        }
                    }),

                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('Is Published?')
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-m-check')
                    ->offIcon('heroicon-m-x-mark')
                    ->beforeStateUpdated(function ($record, $state) {
                        return $record->is_published = $state;
                    })
                    ->afterStateUpdated(function ($record, $state) {
                        return $record->is_published = $state;
                    })

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListHomes::route('/'),
            'create' => Pages\CreateHome::route('/create'),
            'edit' => Pages\EditHome::route('/{record}/edit'),
        ];
    }
}

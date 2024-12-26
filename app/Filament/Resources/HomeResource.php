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

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                            ->hidden(function ($get) {
                                if (!$get('section')) {
                                    return true;
                                } elseif ($get('section') == 4) {
                                    return true;
                                } elseif ($get('section') == 3) {
                                    return true;
                                }

                                return false;
                            })
                            ->columnSpan('full'),

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
                                    ->placeholder('e.g Enjoy Free Wifi, Parking Space, Swimming Pool, etc.'),
                            ])
                            ->label('Icons (Optional)')
                            ->visible(fn($get) => $get('section') == 2)
                            ->addActionLabel('Add another Icon')
                            ->grid(2)
                            ->columnSpan('full'),
                    ])->columnSpan([
                        'md' => 2,
                        'lg' => 2,
                    ]),

                \Filament\Forms\Components\Fieldset::make('Published?')
                    ->schema([
                        \Filament\Forms\Components\ToggleButtons::make('is_published')
                            ->label('Status')
                            ->boolean()
                            ->grouped()
                            ->rule(function ($state, $record) {
                                return [
                                    function (string $attribute, $value, \Closure $fail) use ($state, $record) {
                                        if ($state) {
                                            $existPublished = ContentManagementSystem::where('page', 'home')
                                                ->where('is_published', 1)->where('section', $record->section)->where('id', '!=', $record->id)->exists();

                                            if ($existPublished) {
                                                $fail("Section '{$record->section}' is already published. Please unpublish the existing section before publishing this one.");
                                            }
                                        }
                                    }
                                ];
                            })
                            ->afterStateUpdated(function ($state, $record) {
                                $existPublished = ContentManagementSystem::where('page', 'home')
                                    ->where('is_published', 1)
                                    ->where('section', $record->section)
                                    ->where('id', '!=', $record->id)
                                    ->exists();

                                if ($state && $existPublished) {
                                    $record->is_published = 0;
                                    $record->save();
                                } else {
                                    $record->is_published = $state;
                                    $record->save();
                                }
                            })

                    ])->columnSpan([
                        'md' => 1,
                        'lg' => 1,
                    ])->visible(fn($get) => $get('section')),
            ])->columns([
                'md' => 3,
                'lg' => 3,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('page', 'home'))
            ->columns([
                Tables\Columns\TextColumn::make('section_name')
                    ->label('Section Name')
                    ->searchable(query: fn($query, string $search) => $query->searchBySectionName($search)),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Is Published?')
                    ->icon(fn($record) => $record->is_published == 1 ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn($record) => $record->is_published == 1 ? 'success' : 'danger'),


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

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutUsResource\Pages;
use App\Filament\Resources\AboutUsResource\RelationManagers;
use App\Models\ContentManagementSystem;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AboutUsResource extends Resource
{
    protected static ?string $model = ContentManagementSystem::class;

    protected static ?string $slug = 'about';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $modelLabel = 'About us page';

    protected static ?string $navigationLabel = 'About Us';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        $maxLength = 50;

        return $form
            ->schema([

                \Filament\Forms\Components\Hidden::make('page')
                    ->default('about'),
                \Filament\Forms\Components\Fieldset::make('Page')
                    ->schema([
                        \Filament\Forms\Components\Select::make('section')
                            ->required()
                            ->label('Section Number')
                            ->options([
                                1 => 'Section 1 - Welcome Section',
                                2 => 'Section 2 - Introduction Section',
                                3 => 'Section 3 - Features Section',
                                4 => 'Section 4 - History Section',
                                5 => 'Section 5 - Highlighted FAQ Section'
                            ])
                            ->reactive()
                            ->columnSpan('full')
                            ->afterStateUpdated(fn($set) => $set('value', null)),

                        \Filament\Forms\Components\TextInput::make('title')
                            ->reactive()
                            ->columnSpan('full')
                            ->maxLength($maxLength)
                            ->label(function ($get) {
                                if ($get('section') == 1) {
                                    return 'Tagline';
                                } elseif ($get('section') == 5) {
                                    return 'Question';
                                } else {
                                    return 'Title';
                                }
                            })
                            ->hidden(function ($get) {
                                if (!$get('section')) {
                                    return true;
                                } elseif ($get('section') == 3) {
                                    return true;
                                }

                                return false;
                            })
                            ->hint(
                                function ($get) use ($maxLength) {
                                    $remainingLength = $maxLength - strlen($get('title'));
                                    return $remainingLength > 0
                                        ? "Remaining characters: {$remainingLength} characters"
                                        : "Maximum length reached";
                                }
                            ),

                        \Filament\Forms\Components\Repeater::make('icons')
                            ->schema([
                                \Filament\Forms\Components\FileUpload::make('image')
                                    ->image()
                                    ->label('Icon Image'),
                                \Filament\Forms\Components\TextInput::make('icon_name')
                                    ->label('Name')
                                    ->placeholder('e.g Enjoy Free Wifi, Parking Space, Swimming Pool, etc.'),
                                \Filament\Forms\Components\MarkdownEditor::make('description')
                                    ->label('Description')
                                    ->placeholder('Enter description here.....')
                            ])
                            ->maxItems(4)
                            ->grid(2)
                            ->collapsible()
                            ->label('Features')
                            ->visible(fn($get) => $get('section') == 3)
                            ->addActionLabel('Add another Icon')
                            ->columnSpan('full'),

                        \Filament\Forms\Components\MarkdownEditor::make('value')
                            ->required()
                            ->label(function ($get) {
                                return $get('section') == 5 ? 'Answer' : 'Content';
                            })
                            ->placeholder('Enter content/answer here (e.g. content details, history, FAQ)')
                            ->columnSpan('full')
                            ->hidden(fn($get) => !$get('section') || $get('section') == 3 || $get('section') == 1)
                            ->reactive(),



                    ])->columnSpan([
                        'md' => 2,
                        'lg' => 2,
                    ]),

                \Filament\Forms\Components\Group::make()
                    ->schema([
                        \Filament\Forms\Components\Fieldset::make('Background Image')
                            ->schema([
                                \Filament\Forms\Components\FileUpload::make('background_image')
                                    ->label('')
                                    ->placeholder('Must be high quality image (PNG, JPG, SVG)')
                                    ->columnSpan('full'),

                            ])
                            ->columnSpan([
                                'md' => 1,
                                'lg' => 1,
                            ])->hidden(fn($get) => $get('section') == 3 || $get('section') == 5 || !$get('section')),

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
                                                    $existPublished = ContentManagementSystem::where('page', 'about')
                                                        ->where('is_published', 1)->where('section', $record->section)->where('id', '!=', $record->id)->exists();

                                                    if ($existPublished) {
                                                        $fail("Section '{$record->section}' is already published. Please unpublish the existing section before publishing this one.");
                                                    }
                                                }
                                            }
                                        ];
                                    })
                                    ->afterStateUpdated(function ($state, $record) {
                                        $existPublished = ContentManagementSystem::where('page', 'about')
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

                            ])
                            ->columnSpan([
                                'md' => 1,
                                'lg' => 1,
                            ])->hidden(fn($operation) => $operation == 'create'),
                    ])
            ])->columns([
                'md' => 3,
                'lg' => 3,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('page', 'about'))
            ->columns([
                Tables\Columns\TextColumn::make('section_name')
                    ->label('Section Name')
                    ->searchable(query: fn($query, string $search) => $query->searchBySectionName($search)),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Is Published?')
                    ->icon(fn($record) => $record->is_published == 1 ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn($record) => $record->is_published == 1 ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Published Date')
                    ->getStateUsing(function ($record) {
                        if ($record->is_published) {
                            $datePublished = Carbon::parse($record->updated_at);
                            return $datePublished ? $datePublished->format('M d, Y h:i A') : 'No Date Available';
                        }

                        return 'Not Published Yet';
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
            ])
            ->defaultSort('section', 'asc')
            ->defaultSort('is_published', 'desc');
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
            'index' => Pages\ListAboutUs::route('/'),
            'create' => Pages\CreateAboutUs::route('/create'),
            'edit' => Pages\EditAboutUs::route('/{record}/edit'),
        ];
    }
}
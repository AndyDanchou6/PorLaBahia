<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestaurantMenuResource\Pages;
use App\Filament\Resources\RestaurantMenuResource\RelationManagers;
use App\Models\NewUnit;
use App\Models\RestaurantMenu;
use Closure;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ButtonAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RestaurantMenuResource extends Resource
{
    protected static ?string $model = RestaurantMenu::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Data';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('₱'),
                        Forms\Components\Select::make('category')
                            ->required()
                            ->options([
                                'breakfast' => 'Pamahaw | Breakfast',
                                'special' => 'Espesyal | Special',
                                'chicken' => 'Manok | Chicken',
                                'pork' => 'Baboy | Pork',
                                'beef' => 'Baka | Beef',
                                'rice' => 'Kanin | Rice',
                                'seafoods' => 'Pagkaong Dagat Seafoods',
                                'salad' => 'Insalada | Salad',
                                'soup' => 'Sabaw | Soup',
                                'veggies' => 'Otan | Veggies',
                                'side dish' => 'Side Dish',
                                'dessert' => 'Dessert',
                                'burger' => 'Burger',
                                'pasta' => 'Pasta',
                                'coffee' => 'Kape | Coffee',
                                'juice' => 'Juice',
                                'milkshake' => 'Milkshake',
                                'water' => 'Water',
                                'tea' => 'Tea',
                                'soda' => 'Soda',
                                'beer' => 'Beer',
                            ])
                            ->searchable(),

                        Forms\Components\Select::make('unit_id')
                            ->relationship(name: 'unit', titleAttribute: 'new_unit')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('new_unit')
                                    ->label('New Unit')
                                    ->required()
                                    ->rules([
                                        fn(): Closure => function (string $attribute, $value, Closure $fail) {

                                            $exists = NewUnit::where('new_unit', $value)->exists();

                                            if ($exists) {
                                                $fail('This unit already exists, please choose a different one.');
                                            }
                                        },
                                    ]),


                            ])
                            ->createOptionAction(
                                fn(Forms\Components\Actions\Action $action) => $action
                                    ->modalWidth('2xl')
                                    ->modalHeading('Creating New Unit')
                            )
                            // ->searchable()
                            ->label('Units'),
                    ])->columnSpan([
                        'md' => 2,
                        'lg' => 2,
                    ])->columns(2),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->required(),
                    ])->columnSpan([
                        'md' => 1,
                        'lg' => 1,
                    ])

            ])->columns([
                'md' => 3,
                'lg' => 3,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->square(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Menu Name')
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->prefix('₱')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('category')
                //     ->formatStateUsing(fn($state) => ucwords($state))
                //     ->searchable(),
                Tables\Columns\TextColumn::make('unit.new_unit')
                    ->label('Units')
                    ->searchable()
                    ->default('Not Specified'),
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

                SelectFilter::make('category')
                    ->multiple()
                    ->options([
                        'breakfast' => 'Pamahaw | Breakfast',
                        'special' => 'Espesyal | Special',
                        'chicken' => 'Manok | Chicken',
                        'pork' => 'Baboy | Pork',
                        'beef' => 'Baka | Beef',
                        'rice' => 'Kanin | Rice',
                        'seafoods' => 'Pagkaong Dagat | Seafoods',
                        'salad' => 'Insalada | Salad',
                        'soup' => 'Sabaw | Soup',
                        'veggies' => 'Otan | Veggies',
                        'side dish' => 'Side Dish',
                        'dessert' => 'Dessert',
                        'burger' => 'Burger',
                        'pasta' => 'Pasta',
                        'coffee' => 'Kape | Coffee',
                        'juice' => 'Juice',
                        'milkshake' => 'Milkshake',
                        'water' => 'Water',
                        'tea' => 'Tea',
                        'soda' => 'Soda',
                        'beer' => 'Beer',
                    ])
                    ->label('Filter by Category')
                    ->searchable()
                    ->placeholder('All Categories')
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn($record) => !$record->trashed()),
                Tables\Actions\EditAction::make()
                    ->color('warning')
                    ->visible(fn($record) => !$record->trashed()),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make()
                    ->color('success')
                    ->visible(fn($record) => $record->trashed() && auth()->user()->role === 1),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make()
                        ->color('success')
                        ->visible(fn() => auth()->user()->role === 1),
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
            'index' => Pages\ListRestaurantMenus::route('/'),
            'create' => Pages\CreateRestaurantMenu::route('/create'),
            'view' => Pages\ViewRestaurantMenu::route('/{record}'),
            'edit' => Pages\EditRestaurantMenu::route('/{record}/edit'),
        ];
    }
}

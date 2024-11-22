<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountResource\Pages;
use App\Filament\Resources\DiscountResource\RelationManagers;
use App\Models\Discount;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Markdown;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        TextInput::make('discount_code')
                            ->label('Discount Code')
                            ->required()
                            ->maxLength(10)
                            ->minLength(10)
                            ->unique(ignoreRecord: true),

                        Select::make('discount_type')
                            ->label('Discount Type')
                            ->options([
                                'fixed' => 'Fixed',
                                'percentage' => 'Percentage'
                            ])
                            ->required(),

                        TextInput::make('value')
                            ->integer()
                            ->required(),

                        DatePicker::make('expiration_date')
                            ->label('Expiration Date')
                            ->required(),
                    ])->columns(2),

                Section::make('Restrictions')
                    ->schema([
                        Toggle::make('usage_limit')
                            ->label('Usage Limit')
                            ->nullable(),

                        Toggle::make('stacking_restriction')
                            ->label('Stacking Restriction')
                            ->nullable(),

                        TextInput::make('minimum_order')
                            ->label('Minimum Order')
                            ->nullable(),

                        TextInput::make('maximum_order')
                            ->label('Maximum Order')
                            ->nullable(),

                        TextInput::make('applicability')
                            ->nullable(),
                    ])->columns(2),

                MarkdownEditor::make('description')
                    ->nullable(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('discount_code')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('description_code')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('discount_type')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('value')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('expiration_date')
                    ->sortable()
                    ->searchable()
                    ->dateTime()
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
            'view' => Pages\ViewDiscount::route('/{record}'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->role == 1;
    }
}

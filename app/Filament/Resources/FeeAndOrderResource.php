<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeeAndOrderResource\Pages;
use App\Filament\Resources\FeeAndOrderResource\RelationManagers;
use App\Models\FeeAndOrder;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Filament\Forms;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeeAndOrderResource extends Resource
{
    protected static ?string $model = FeeAndOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Master Records';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        ToggleButtons::make('category')
                            ->label('Category')
                            ->options([
                                'order' => 'Order',
                                'fee' => 'Fee'
                            ])
                            ->inline()
                            ->default('order')
                            ->reactive()
                            ->afterStateUpdated(function ($set, $state) {
                                if ($state === 'order') {
                                    $set('fee_name', null);
                                    $set('charge', null);
                                } elseif ($state === 'fee') {
                                    $set('item', null);
                                    $set('quantity', null);
                                    $set('price', null);
                                    $set('order_date', null);
                                }
                            }),

                        Select::make('reservation_id')
                            ->label('Reservation')
                            ->options(function () {
                                return Reservation::inRandomOrder()
                                    ->limit(5)
                                    ->get()
                                    ->pluck('booking_reference_no', 'id');
                            })
                            ->required()
                            ->searchable(),
                    ])->columnSpan([
                        'md' => 1,
                    ]),

                Section::make()
                    ->schema([
                        Fieldset::make('Order Details')
                            ->schema([
                                TextInput::make('item')
                                    ->label('Item Name')
                                    ->required(),
                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('price')
                                    ->label('Price')
                                    ->numeric()
                                    ->step(0.01)
                                    ->required(),
                            ])
                            ->hidden(fn($get) => $get('category') !== 'order'),

                        Fieldset::make('Fee Details')
                            ->schema([
                                TextInput::make('fee_name')
                                    ->label('Fee Name')
                                    ->required(),
                                TextInput::make('charge')
                                    ->label('Charge')
                                    ->numeric()
                                    ->step(0.01)
                                    ->required(),
                            ])
                            ->hidden(fn($get) => $get('category') !== 'fee'),
                    ])->columnSpan([
                        'md' => 2,
                    ]),

            ])->columns([
                'md' => 3,
                'lg' => 3,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reservation.booking_reference_no')
                    ->searchable(),
                TextColumn::make('category')
                    ->formatStateUsing(function ($state) {
                        return ucfirst($state);
                    })
                    ->searchable(),
                TextColumn::make('item')
                    ->label('Name')
                    ->getStateUsing(function ($record) {
                        if ($record->category === 'order') {
                            return $record->item ?? 'No Item';
                        }

                        return $record->fee_name ?? 'No Fee Name';
                    })
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Created by')
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('category')
                ->options([
                    'fee' => 'Fee',
                    'order' => 'Order',
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn($record) => !$record->trashed()),
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => !$record->trashed()),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make()
                    ->visible(function ($record) {
                        return auth()->check() && auth()->user()->role === 1 && $record->trashed();
                    })
                    ->color('success'),
                Tables\Actions\ForceDeleteAction::make()
                    ->visible(function ($record) {
                        return auth()->check() && auth()->user()->role === 1 && $record->trashed();
                    }),
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
            'index' => Pages\ListFeeAndOrders::route('/'),
            'create' => Pages\CreateFeeAndOrder::route('/create'),
            'view' => Pages\ViewFeeAndOrders::route('/{record}'),
            'edit' => Pages\EditFeeAndOrder::route('/{record}/edit'),
        ];
    }
}

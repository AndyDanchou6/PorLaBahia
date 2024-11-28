<?php

namespace App\Filament\Resources\ReservationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use App\Models\Reservation;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeesAndOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'feesAndOrders';

    public function form(Form $form): Form
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
                    ]),

                Fieldset::make('Order Details')
                    ->schema([
                        TextInput::make('item')->label('Item Name')->required(),
                        TextInput::make('quantity')->label('Quantity')->required(),
                        TextInput::make('price')->label('Price')->required(),
                    ])
                    ->hidden(fn($get) => $get('category') !== 'order'),

                Fieldset::make('Fee Details')
                    ->schema([
                        TextInput::make('fee_name')->label('Fee Name')->required(),
                        TextInput::make('charge')->label('Charge')->required(),
                    ])
                    ->hidden(fn($get) => $get('category') !== 'fee'),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reservation_id')
            ->columns([
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(function ($record) {
                        return !$record->trashed();
                    }),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make()
                    ->visible(function ($record) {
                        return auth()->check() && auth()->user()->role === 1 && $record->trashed();
                    }),
                Tables\Actions\ForceDeleteAction::make()
                    ->visible(function ($record) {
                        return auth()->check() && auth()->user()->role === 1 && $record->trashed();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->visible(function () {
                            return auth()->check() && auth()->user()->role === 1;
                        }),
                    Tables\Actions\RestoreBulkAction::make()
                        ->visible(function () {
                            return auth()->check() && auth()->user()->role === 1;
                        }),
                ]),
            ]);
    }
}

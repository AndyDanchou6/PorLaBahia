<?php

namespace App\Filament\Resources\ReservationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Order;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('item')
                    ->required(),
                TextInput::make('quantity')
                    ->integer()
                    ->required(),
                TextInput::make('price')
                    ->integer()
                    ->required(),
                DateTimePicker::make('order_date')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item')
            ->columns([
                Tables\Columns\TextColumn::make('item')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('order_date')
                    ->sortable()
                    ->searchable()
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn(Order $record) => !$record->trashed())
                    ->color('warning'),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make()
                    ->visible(function (Order $record) {
                        return $record->trashed() && auth()->user()->role == 1;
                    })
                    ->color('success'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

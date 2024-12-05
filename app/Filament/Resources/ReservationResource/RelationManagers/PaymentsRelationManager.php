<?php

namespace App\Filament\Resources\ReservationResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('reservation_id')
                //     ->required()
                //     ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->prefix('₱')
                    ->required(),
                Forms\Components\Select::make('payment_method')
                    ->options([
                        'g-cash' => 'G-Cash',
                        'cash' => 'Cash',
                    ])
                    ->required(),
                Forms\Components\Hidden::make('payment_status')
                    ->default('paid')
                    ->required(),
                // Forms\Components\Hidden::make('expiration_date')
                //     ->default(Carbon::now()->addHours(12)),
                // ->required(),
                // Forms\Components\Hidden::make('status')
                //     ->default('onhold'),
                //     ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Payment')
            ->columns([
                // Tables\Columns\TextColumn::make('reservation_id'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->prefix('₱'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->label('Payment Method'),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('New Payment'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeeResource\Pages;
use App\Filament\Resources\FeeResource\RelationManagers;
use App\Models\Fee;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeeResource extends Resource
{
    protected static ?string $model = Fee::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Order and Fee Records';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('reservation_id')
                    ->label('Booking Reference Number')
                    ->options(function () {
                        return Reservation::all()->pluck('booking_reference_no', 'id');
                    })
                    ->required(),
                TextInput::make('fee_name')
                    ->required(),
                TextInput::make('charge')
                    ->integer()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reservation_id')
                ->label('Booking Reference No')
                ->formatStateUsing(fn($record) => $record->reservation->booking_reference_no)
                ->searchable(),
                TextColumn::make('fee_name')
                ->searchable(),
                TextColumn::make('charge')
                ->prefix('₱ ')
                ->searchable()
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn(Fee $record) => !$record->trashed())
                    ->color('warning'),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make()
                    ->visible(function (Fee $record) {
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFees::route('/'),
            'create' => Pages\CreateFee::route('/create'),
            'edit' => Pages\EditFee::route('/{record}/edit'),
        ];
    }
}

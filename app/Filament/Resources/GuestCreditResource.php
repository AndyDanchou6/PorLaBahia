<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuestCreditResource\Pages;
use App\Filament\Resources\GuestCreditResource\RelationManagers;
use App\Models\GuestCredit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class GuestCreditResource extends Resource
{
    protected static ?string $model = GuestCredit::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('guest_id')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($record) {
                        return $record->guest->first_name . ' ' . $record->guest->last_name;
                    }),
                // TextColumn::make('booking_reference_no')
                //     ->sortable()
                //     ->searchable(),
                TextColumn::make('amount')
                    ->sortable()
                    ->searchable()
                    ->prefix('₱'),
                TextColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($record) => ucfirst($record->status)),
                TextColumn::make('expiration_date')
                    ->sortable()
                    ->searchable()
                    ->dateTime(),
                IconColumn::make('is_redeemed')
                    ->sortable()
                    ->searchable()
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                TextColumn::make('date_redeemed')
                    ->searchable()
                    ->default('Not Redeemed Yet')
                    ->formatStateUsing(function ($record) {
                        if ($record->date_redeemed) {
                            return Carbon::parse($record->date_redeemed)->toDayDateTimeString();
                        } else {
                            return 'Not Redeemed Yet';
                        }
                    }),
            ])
            ->filters([
                TernaryFilter::make('is_redeemed'),
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'expired' => 'Expired',
                    ]),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListGuestCredits::route('/'),
            // 'create' => Pages\CreateGuestCredit::route('/create'),
            // 'edit' => Pages\EditGuestCredit::route('/{record}/edit'),
        ];
    }
}

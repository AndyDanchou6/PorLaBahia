<?php

namespace App\Filament\Resources\GuestInfoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class GuestCreditRelationManager extends RelationManager
{
    protected static string $relationship = 'guestCredit';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('guest_id')
            ->columns([
                TextColumn::make('coupon')
                    ->sortable()
                    ->searchable(),
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
                    'redeemed' => 'Redeemed',
                ]),
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

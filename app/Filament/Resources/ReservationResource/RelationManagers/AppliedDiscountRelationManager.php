<?php

namespace App\Filament\Resources\ReservationResource\RelationManagers;

use App\Models\Discount;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppliedDiscountRelationManager extends RelationManager
{
    protected static string $relationship = 'appliedDiscount';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('discount_id')
                    ->label('Discount Code')
                    ->required()
                    ->options(function () {
                        return Discount::inRandomOrder()
                            ->limit(5)
                            ->pluck('discount_code', 'id');
                    })->columnSpan(1),

                MarkdownEditor::make('notes')
                    ->nullable()
                    ->columnSpan(2),
            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('notes')
            ->columns([
                TextColumn::make('discount_id')
                    ->label('Discount Code')
                    ->formatStateUsing(function ($record) {
                        return $record->discount->discount_code;
                    }),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make()
                    ->visible(function ($record) {
                        return auth()->user()->role === 1 && $record->trashed();
                    })
                    ->color('success'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make()
                        ->visible(function () {
                            return auth()->user()->role == 1;
                        })->color('success'),
                ]),
            ]);
    }
}

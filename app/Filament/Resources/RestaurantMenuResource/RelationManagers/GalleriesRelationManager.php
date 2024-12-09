<?php

namespace App\Filament\Resources\RestaurantMenuResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GalleriesRelationManager extends RelationManager
{
    protected static string $relationship = 'galleries';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->multiple()
                            ->image()
                            ->columnSpan(2),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('category_name')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('All Images')
                    ->stacked()
                    ->reorderable()
                    ->appendFiles()
                    ->image()
                    ->circular(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('New Galleries'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('warning'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

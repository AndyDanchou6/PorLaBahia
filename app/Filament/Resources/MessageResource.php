<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Filament\Resources\MessageResource\RelationManagers;
use App\Models\Message;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Data';

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
                Tables\Columns\TextColumn::make('contact_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact_no')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->label('Replied')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('status')
                    ->label('Replied')
                    ->placeholder('All Messages')
                    ->trueLabel('Replied')
                    ->falseLabel('Not Replied'),
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('contact_name')
                    ->color('primary'),
                Infolists\Components\TextEntry::make('contact_no')
                    ->color('primary'),
                Infolists\Components\TextEntry::make('email')
                    ->color('primary'),
                Infolists\Components\TextEntry::make('street')
                    ->color('primary'),
                Infolists\Components\TextEntry::make('city')
                    ->color('primary'),
                Infolists\Components\TextEntry::make('zip_code')
                    ->color('primary'),
                Infolists\Components\TextEntry::make('status')
                    ->formatStateUsing(fn($state) => $state == 1 ? 'Replied' : 'Not Replied')
                    ->color('primary'),
                Infolists\Components\TextEntry::make('message')
                    ->columnSpanFull(),

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
            'index' => Pages\ListMessages::route('/'),
            'create' => Pages\CreateMessage::route('/create'),
            // 'edit' => Pages\EditMessage::route('/{record}/edit'),
        ];
    }
}

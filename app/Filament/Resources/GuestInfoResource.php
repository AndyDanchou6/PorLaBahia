<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuestInfoResource\Pages;
use App\Filament\Resources\GuestInfoResource\RelationManagers;
use App\Models\GuestInfo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GuestInfoResource extends Resource
{
    protected static ?string $model = GuestInfo::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Guest';

    protected static ?string $modelLabel = 'Guest Information';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter First Name'),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter Last Name'),
                Forms\Components\TextInput::make('contact_no')
                    ->required()
                    ->numeric()
                    ->placeholder('Enter Contact Number'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter Email'),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter Address'),
                Forms\Components\TextInput::make('fb_name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter Facebook Name (Optional)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable()
                    ->label('First Name')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable()
                    ->label('Last Name')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('contact_no')
                    ->searchable()
                    ->formatStateUsing(fn($state) => '0' . ltrim($state, '0'))
                    ->label('Contact Number')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fb_name')
                    ->formatStateUsing(fn($state) => $state ?: 'N/A')
                    ->searchable()
                    ->label('Facebook Name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->label(''),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->label(''),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->label(''),
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
            'index' => Pages\ListGuestInfos::route('/'),
            'create' => Pages\CreateGuestInfo::route('/create'),
            'view' => Pages\ViewGuestInfo::route('/{record}'),
            'edit' => Pages\EditGuestInfo::route('/{record}/edit'),
        ];
    }
}

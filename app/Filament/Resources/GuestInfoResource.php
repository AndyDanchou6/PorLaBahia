<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuestInfoResource\Pages;
use App\Filament\Resources\GuestInfoResource\RelationManagers;
use App\Models\GuestInfo;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GuestInfoResource extends Resource
{
    protected static ?string $model = GuestInfo::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Guests';

    protected static ?string $modelLabel = 'Guest Information';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('first_name')
                                    ->label('First Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter First Name'),
                                Forms\Components\TextInput::make('last_name')
                                    ->label('Last Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter Last Name'),
                                Forms\Components\TextInput::make('contact_no')
                                    ->label('Contact Number')
                                    ->required()
                                    ->numeric()
                                    ->placeholder('Enter Contact Number'),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->maxLength(255)
                                    ->rules([
                                        fn(Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                            $email = $get('email');
                                            $isEmailExist = GuestInfo::where('email', $email)->exists();

                                            if ($isEmailExist) {
                                                $fail("{$email} has already been used. Please provide a unique email.");
                                            }
                                        }
                                    ])
                                    ->placeholder('Enter Email'),
                                Forms\Components\TextInput::make('address')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter Address'),
                                Forms\Components\TextInput::make('fb_name')
                                    ->label('Facebook Name')
                                    ->maxLength(255)
                                    ->placeholder('Enter Facebook Name (Optional)'),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable(['first_name', 'last_name'])
                    ->label('Full Name')
                    ->searchable()
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('contact_no')
                    ->searchable()
                    ->formatStateUsing(fn($state) => '0' . ltrim($state, '0'))
                    ->label('Contact Number')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->searchable(),
                Tables\Columns\TextColumn::make('fb_name')
                    ->default('No Information')
                    ->formatStateUsing(fn($state) => ucwords($state))
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->visible(fn($record) => !$record->trashed()),
                    Tables\Actions\EditAction::make()
                        ->color('warning')
                        ->visible(fn($record) => !$record->trashed()),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make()
                        ->color('success'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\GuestCreditRelationManager::class,
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

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $count = User::whereNull('deleted_at')->count();

        if ($count == 0) {
            return null;
        }

        return $count;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->required()->email()->unique(ignoreRecord: true),
                TextInput::make('password')->password(),
                Select::make('role')
                    ->label('Role')
                    ->options([
                        1 => 'Admin',
                        0 => 'Staff',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn($record) => $record->roleLabel()),
                IconColumn::make('status')
                    ->label('Active Account')
                    ->icon(fn($record) => $record->status == true ? 'heroicon-o-check-circle' : 'heroicon-o-minus-circle')
                    ->color(fn($record) => $record->status == true ? 'success' : 'gray'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\TernaryFilter::make('suspended')
                    ->placeholder('All users')
                    ->trueLabel('Suspended users')
                    ->falseLabel('Active users')
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn($record) => !$record->trashed()),
                Tables\Actions\EditAction::make()
                    ->color('warning')
                    ->visible(fn($record) => !$record->trashed()),
                // Tables\Actions\DeleteAction::make()
                //     ->visible(fn($record) => $record->role !== 1),
                Tables\Actions\RestoreAction::make()
                    ->color('success'),
                Action::make('suspend')
                    ->label('Suspend')
                    ->icon('heroicon-o-user-minus')
                    ->color('danger')
                    ->action(function ($record) {
                        $record->status = false;
                        $record->save();

                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('User Suspended!')
                            ->body("$record->name has been suspended")
                            ->send();
                    })
                    ->visible(fn($record) => $record->roleLabel() !== 'Admin' && $record->status == true),

                Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->action(function ($record) {
                        $record->status = true;
                        $record->save();

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('User Activated!')
                            ->body("$record->name has been activated")
                            ->send();
                    })
                    ->visible(fn($record) => $record->roleLabel() !== 'Admin' && $record->status == false)

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->role == 1;
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Filament\Resources\ReservationResource\RelationManagers\FeesRelationManager;
use App\Filament\Resources\ReservationResource\RelationManagers\OrdersRelationManager;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Accommodation;
use App\Models\GuestInfo;
use App\Models\Discount;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        Select::make('accommodation_id')
                            ->label('Room Name')
                            ->options(function () {
                                return Accommodation::inRandomOrder()
                                    ->limit(5)
                                    ->get()
                                    ->pluck('room_name', 'id');
                            })
                            ->searchable()
                            ->required(),
                        Select::make('guest_id')
                            ->label('Guest Name')
                            ->options(function () {
                                return GuestInfo::inRandomOrder()
                                    ->limit(5)
                                    ->get()
                                    ->mapWithKeys(function ($guest) {
                                        return [$guest->id => "{$guest->first_name} {$guest->last_name}"];
                                    });
                            })
                            ->searchable()
                            ->required(),
                        Select::make('discount_id')
                            ->label('Discount')
                            ->options(function () {
                                return Discount::inRandomOrder()
                                    ->limit(5)
                                    ->get()
                                    ->pluck('discount_code', 'id');
                            })
                            ->searchable()
                            ->nullable(),
                        TextInput::make('booking_reference_no')
                            ->label('Booking Reference Number')
                            ->default(fn() => (new Reservation())->generateBookingReference())
                            ->readOnly()
                            ->required(),
                        TextInput::make('booking_fee')
                            ->integer()
                            ->required(),
                    ])->columns(2),

                Section::make('Reservation Date')
                    ->schema([
                        DatePicker::make('check_in_date')
                            ->required()
                            ->date(),
                        DatePicker::make('check_out_date')
                            ->required()
                            ->date(),
                    ]),


                Section::make('Payment Details')
                    ->schema([
                        Select::make('payment_method')
                            ->options([
                                'gcash' => 'G-Cash',
                                'cash' => 'Cash',
                            ])
                            ->required(),
                        Select::make('payment_status')
                            ->options([
                                'unpaid' => 'Unpaid',
                                'partial' => 'Partially Paid',
                                'paid' => 'Fully Paid',
                            ])
                            ->required(),
                    ]),

                Section::make('')
                    ->schema([
                        Toggle::make('booking_status')
                            ->default(true),
                    ])->hiddenOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_reference_no')
                    ->searchable(),
                TextColumn::make('check_in_date')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('check_out_date')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('booking_fee')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make()
                        ->visible(fn($record) => $record->trashed()),
                    Tables\Actions\RestoreAction::make()
                        ->color('success'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class,
            FeesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'view' => Pages\ViewReservation::route('/{record}'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }
}
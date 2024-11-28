<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Filament\Resources\ReservationResource\RelationManagers\FeesAndOrdersRelationManager;
use Illuminate\Support\Carbon;
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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;

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
                Group::make()
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
                        DatePicker::make('check_in_date')
                            ->required()
                            ->date()
                            ->minDate(today())
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                $set('check_out_date', null);
                            }),
                        DatePicker::make('check_out_date')
                            ->required()
                            ->date()
                            ->reactive()
                            ->disabled(fn($get) => !$get('check_in_date'))
                            ->minDate(function ($get) {
                                $checkInDate = $get('check_in_date');
                                return $checkInDate ? Carbon::parse($checkInDate)->addDay() : today()->addDay();
                            }),
                    ])->columnSpan([
                        'md' => 2,
                        'lg' => 2,
                    ])->columns(2),

                Section::make()
                    ->schema([
                        Group::make()
                            ->schema([
                                TextInput::make('booking_reference_no')
                                    ->label('Booking Reference Number')
                                    ->default(fn() => (new Reservation())->generateBookingReference())
                                    ->readOnly(),
                                Toggle::make('booking_status')
                                    ->label('Booking Status')
                                    ->default(true)
                                    ->disabled(fn($context) => $context === 'create'),
                            ]),
                    ])->columnSpan([
                        'md' => 1,
                        'lg' => 1,
                    ]),
                Group::make()
                    ->schema([
                        TextInput::make('total_payable')
                            ->numeric()
                            ->step(0.01)
                            ->default(fn($record) => $record->total_payable ?? 0.00)
                            ->visible(fn($context) => $context === 'view' || $context === 'edit')
                            ->afterStateHydrated(function ($state, $set, $record) {
                                $set('total_payable', $state ?? $record->total_payable ?? 0.00);
                            }),
                        TextInput::make('total_paid')
                            ->numeric()
                            ->step(0.01)
                            ->default(fn($record) => $record->total_paid ?? 0.00)
                            ->visible(fn($context) => $context === 'view' || $context === 'edit')
                            ->afterStateHydrated(function ($state, $set, $record) {
                                $set('total_paid', $state ?? $record->total_paid ?? 0.00);
                            }),
                        TextInput::make('balance')
                            ->numeric()
                            ->step(0.01)
                            ->default(fn($record) => $record->balance ?? 0.00)
                            ->visible(fn($context) => $context === 'view' || $context === 'edit')
                            ->afterStateHydrated(function ($state, $set, $record) {
                                $set('balance', $state ?? $record->balance ?? 0.00);
                            }),
                    ])->hidden(fn() => auth()->user()->role !== 1)
                    ->columnSpan(3)->columns([
                        'md' => 3,
                        'lg' => 3,
                    ]),
            ])->columns([
                'md' => 3,
                'lg' => 3,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_reference_no')
                    ->searchable(),
                TextColumn::make('guest_id')
                    ->label('Guest Name')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->guest->first_name . ' ' . $record->guest->last_name;
                    }),
                TextColumn::make('check_in_date')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('check_out_date')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Filter::make('Today')
                    ->query(function ($query) {
                        return $query->where('check_in_date', '=', Carbon::today());
                    }),
                Filter::make('Past Reservations')
                    ->query(function ($query) {
                        return $query->where('check_in_date', '<', Carbon::today())
                            ->where('check_out_date', '<', Carbon::today());
                    }),
                Filter::make('Present Reservations')
                    ->query(function ($query) {
                        return $query->where('check_in_date', '>=', Carbon::today())
                        ->orWhere('check_out_date', '>=', Carbon::today());
                    }),
                Filter::make('Last Month')
                    ->query(function ($query) {
                        return $query->where('check_in_date', '>=', Carbon::now()->subMonth()->startOfMonth())
                            ->where('check_out_date', '<=', Carbon::now()->subWeek()->endOfMonth());
                    }),
                Filter::make('Last Week')
                    ->query(function ($query) {
                        return $query->where('check_in_date', '>=', Carbon::now()->subWeek()->startOfWeek())
                            ->where('check_out_date', '<=', Carbon::now()->subWeek()->endOfWeek());
                    }),
                Filter::make('This Week')
                    ->query(function ($query) {
                        return $query->where('check_in_date', '>=', Carbon::today())
                            ->where('check_out_date', '<=', Carbon::today()->addWeek());
                    }),
                Filter::make('This Month')
                    ->query(function ($query) {
                        return $query->where('check_in_date', '>=', Carbon::today())
                            ->where('check_out_date', '<=', Carbon::today()->addMonth());
                    }),
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
            FeesAndOrdersRelationManager::class,
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

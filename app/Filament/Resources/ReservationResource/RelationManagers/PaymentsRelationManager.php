<?php

namespace App\Filament\Resources\ReservationResource\RelationManagers;

use App\Filament\Resources\ReservationResource;
use App\Models\GuestInfo;
use App\Models\Payment;
use App\Models\Reservation;
use Carbon\Carbon;
use Closure;
use Dotenv\Parser\Value;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->prefix('₱')
                    ->required()
                    ->stripCharacters(',')
                    ->reactive(),

                \Filament\Forms\Components\Select::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'GCash' => 'GCash',
                        'credits' => 'Credits',
                    ])
                    ->required()
                    ->reactive(),

                \Filament\Forms\Components\TextInput::make('gcash_reference_number')
                    ->visible(function ($get) {
                        $paymentMethod = $get('payment_method');

                        if ($paymentMethod == 'GCash') {
                            return true;
                        }

                        return false;
                    })
                    ->reactive()
                    ->label('Gcash Reference #')
                    ->required(),

                \Filament\Forms\Components\TextInput::make('payment_status')
                    ->reactive()
                    ->label('Payment Status')
                    ->required(),

                \Filament\Forms\Components\FileUpload::make('gcash_screenshot')
                    ->visible(function ($get) {
                        $paymentMethod = $get('payment_method');

                        if ($paymentMethod == 'GCash') {
                            return true;
                        }

                        return false;
                    })
                    ->image()
                    ->reactive()
                    ->label('Gcash Screenshot')
                    ->columnSpan('full'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Payment')
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->prefix('₱'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'GCash' => 'success',
                        'cash' => 'info',
                        'credits' => 'warning',
                    })
                    ->label('Payment Method'),

                Tables\Columns\TextColumn::make('payment_status')
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'void' => 'gray',
                    })
                    ->label('Payment Status'),
            ])
            ->filters([
                // Tables\Filters\TrashedFilter::make()
                //     ->visible(fn() => Auth::user()->role == 1),
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                // Action::make('Paid')
                //     ->icon('heroicon-o-check-badge')
                //     ->requiresConfirmation()
                //     ->modalIcon('heroicon-o-banknotes')
                //     ->modalHeading('Confirm GCash Payment')
                //     ->modalDescription('Are you sure you want to mark this reservation as paid via GCash? This action cannot be undone.')
                //     ->modalSubmitActionLabel('Yes, Mark as Paid')
                //     ->action(function ($record) {

                //         $record->payment_status = 'paid';

                //         $record->save();

                //         Notification::make()
                //             ->title('GCash Payment Successful')
                //             ->body('The reservation payment has been marked as successfully paid via GCash.')
                //             ->success()
                //             ->send();

                //         $this->redirect(ReservationResource::getUrl('view', ['record' => $record->reservation_id]));
                //     })
                //     ->visible(function ($record) {
                //         if ($record->payment_status == 'paid' && $record->payment_status == 'void') {
                //             return false;
                //         }

                //         if ($record->payment_method == 'GCash' && $record->payment_status == 'pending') {
                //             return true;
                //         }

                //         return;
                //     })
                //     ->color('success'),

                // Action::make('void')
                //     ->color('warning')
                //     ->icon('heroicon-o-trash')
                //     ->action(function ($record) {
                //         $record->payment_status = 'void';
                //         $record->save();

                //         Notification::make()
                //             ->title('Payment Voided Successfully')
                //             ->body('Payment record has been marked as void.')
                //             ->success()
                //             ->send();

                //         $this->redirect(ReservationResource::getUrl('view', ['record' => $record->reservation_id]));
                //     })
                //     ->hidden(function ($record) {
                //         // $viewUrl = ReservationResource::getUrl('view', ['record' => $record->reservation_id]);

                //         if ($record->getOriginal('payment_status') == 'void') {
                //             return true;
                //         }

                //         // if($viewUrl){
                //         //     return true;

                //         // }

                //         return false;
                //     })


            ])
            ->defaultSort(function ($query) {
                $query->orderByRaw("FIELD(payment_status, 'paid', 'unpaid', 'void')");
            });
    }
}

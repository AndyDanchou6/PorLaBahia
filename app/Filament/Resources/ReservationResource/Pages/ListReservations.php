<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use Filament\Resources\Components\Tab;

class ListReservations extends ListRecords
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->badge(\App\Models\Reservation::query()->count()),
            'active' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('booking_status', 'active'))
                ->badge(\App\Models\Reservation::query()->where('booking_status', 'active')->count())
                ->badgeColor('success'),
            'pending' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('booking_status', 'pending'))
                ->badge(\App\Models\Reservation::query()->where('booking_status', 'pending')->count())
                ->badgeColor('info'),
            'on_hold' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('booking_status', 'on_hold'))
                ->badge(\App\Models\Reservation::query()->where('booking_status', 'on_hold')->count())
                ->badgeColor('info'),
            'expired' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('booking_status', 'expired'))
                ->badge(\App\Models\Reservation::query()->where('booking_status', 'expired')->count())
                ->badgeColor('gray'),
            'cancelled' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('booking_status', 'cancelled'))
                ->badge(\App\Models\Reservation::query()->where('booking_status', 'cancelled')->count())
                ->badgeColor('gray'),
        ];
    }
}

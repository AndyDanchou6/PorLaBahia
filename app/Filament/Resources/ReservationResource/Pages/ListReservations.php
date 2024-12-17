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
            'all' => Tab::make(),
            'active' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('booking_status', 'active')),
            'pending' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('booking_status', 'pending')),
            'on_hold' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('booking_status', 'on_hold')),
            'expired' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('booking_status', 'expired')),
            'cancelled' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('booking_status', 'cancelled')),

        ];
    }
}

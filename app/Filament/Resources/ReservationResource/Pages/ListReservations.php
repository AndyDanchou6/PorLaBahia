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
                ->badge(fn() => \App\Models\Reservation::query()->where('booking_status', 'active')->count() > 0 ?
                    \App\Models\Reservation::query()->where('booking_status', 'active')->count() : null)
                ->badgeColor('success'),
            'pending' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('booking_status', 'pending'))
                ->badge(fn() => \App\Models\Reservation::query()->where('booking_status', 'pending')->count() > 0 ?
                    \App\Models\Reservation::query()->where('booking_status', 'pending')->count() : null)
                ->badgeColor('info'),
            'on_hold' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('booking_status', 'on_hold'))
                ->badge(fn() => \App\Models\Reservation::query()->where('booking_status', 'on_hold')->count() > 0 ?
                    \App\Models\Reservation::query()->where('booking_status', 'on_hold')->count() : null)
                ->badgeColor('info'),
            'finished' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('booking_status', 'finished'))
                ->badge(fn() => \App\Models\Reservation::query()->where('booking_status', 'finished')->count() > 0 ?
                    \App\Models\Reservation::query()->where('booking_status', 'finished')->count() : null)
                ->badgeColor('gray'),
            'expired' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('booking_status', 'expired'))
                ->badge(fn() => \App\Models\Reservation::query()->where('booking_status', 'expired')->count() > 0 ?
                    \App\Models\Reservation::query()->where('booking_status', 'expired')->count() : null)
                ->badgeColor('danger'),
            'cancelled' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('booking_status', 'cancelled'))
                ->badge(fn() => \App\Models\Reservation::query()->where('booking_status', 'cancelled')->count() > 0 ?
                    \App\Models\Reservation::query()->where('booking_status', 'cancelled')->count() : null)
                ->badgeColor('danger'),
        ];
    }
}

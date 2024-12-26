<?php

namespace App\Filament\Resources\GuestInfoResource\Pages;

use App\Filament\Resources\GuestInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\GuestInfo;

class ListGuestInfos extends ListRecords
{
    protected static string $resource = GuestInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Guest'),
        ];
    }


    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->badge(\App\Models\GuestInfo::query()->count()),
            'with_credits' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->whereHas('guestCredit', function ($query) {
                    $query->where('status', 'active')
                        ->where('is_redeemed', false);
                }))
                ->badge(fn() => GuestInfo::whereHas('guestCredit', function ($query) {
                    $query->where('status', 'active')
                        ->where('is_redeemed', false);
                })->count())
                ->badgeColor('success'),
            'no_credits' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->whereDoesntHave('guestCredit', (function ($query) {
                    $query->where('status', 'active')
                        ->where('is_redeemed', false);
                })))
                ->badge(fn() => \App\Models\GuestInfo::whereDoesntHave('guestCredit', (function ($query) {
                    $query->where('status', 'active')
                        ->where('is_redeemed', false);
                }))
                    ->count())
                ->badgeColor('info'),
        ];
    }
}

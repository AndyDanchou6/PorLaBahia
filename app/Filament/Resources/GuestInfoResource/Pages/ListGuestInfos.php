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
}

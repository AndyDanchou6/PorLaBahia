<?php

namespace App\Filament\Resources\GuestInfoResource\Pages;

use App\Filament\Resources\GuestInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGuestInfos extends ListRecords
{
    protected static string $resource = GuestInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

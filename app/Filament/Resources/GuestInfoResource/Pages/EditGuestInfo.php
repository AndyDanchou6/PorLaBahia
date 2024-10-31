<?php

namespace App\Filament\Resources\GuestInfoResource\Pages;

use App\Filament\Resources\GuestInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGuestInfo extends EditRecord
{
    protected static string $resource = GuestInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\GuestCreditResource\Pages;

use App\Filament\Resources\GuestCreditResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGuestCredit extends EditRecord
{
    protected static string $resource = GuestCreditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

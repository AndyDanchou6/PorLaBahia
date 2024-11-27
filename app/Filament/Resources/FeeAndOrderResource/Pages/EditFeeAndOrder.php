<?php

namespace App\Filament\Resources\FeeAndOrderResource\Pages;

use App\Filament\Resources\FeeAndOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeeAndOrder extends EditRecord
{
    protected static string $resource = FeeAndOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('back')
                ->url(FeeAndOrderResource::getUrl())
                ->button()
                ->color('gray'),
        ];
    }
}

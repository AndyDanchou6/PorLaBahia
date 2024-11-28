<?php

namespace App\Filament\Resources\FeeAndOrderResource\Pages;

use App\Filament\Resources\FeeAndOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFeeAndOrders extends ViewRecord
{
    protected static string $resource = FeeAndOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('warning'),
            Actions\Action::make('back')
                ->url(FeeAndOrderResource::getUrl())
                ->button()
                ->color('gray'),
        ];
    }
}

<?php

namespace App\Filament\Resources\FeeAndOrderResource\Pages;

use App\Filament\Resources\FeeAndOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeeAndOrders extends ListRecords
{
    protected static string $resource = FeeAndOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

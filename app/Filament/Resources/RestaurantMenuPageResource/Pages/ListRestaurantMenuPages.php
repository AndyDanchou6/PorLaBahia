<?php

namespace App\Filament\Resources\RestaurantMenuPageResource\Pages;

use App\Filament\Resources\RestaurantMenuPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRestaurantMenuPages extends ListRecords
{
    protected static string $resource = RestaurantMenuPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

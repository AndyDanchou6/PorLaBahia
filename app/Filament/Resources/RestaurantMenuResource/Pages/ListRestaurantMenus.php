<?php

namespace App\Filament\Resources\RestaurantMenuResource\Pages;

use App\Filament\Resources\RestaurantMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRestaurantMenus extends ListRecords
{
    protected static string $resource = RestaurantMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Menu'),
        ];
    }
}

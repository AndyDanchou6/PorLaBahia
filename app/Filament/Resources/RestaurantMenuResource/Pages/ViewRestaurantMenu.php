<?php

namespace App\Filament\Resources\RestaurantMenuResource\Pages;

use App\Filament\Resources\RestaurantMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRestaurantMenu extends ViewRecord
{
    protected static string $resource = RestaurantMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('back')
                ->url(RestaurantMenuResource::getUrl())
                ->button()
                ->color('gray'),
        ];
    }
}

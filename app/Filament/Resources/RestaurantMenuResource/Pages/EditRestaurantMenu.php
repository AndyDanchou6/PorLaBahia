<?php

namespace App\Filament\Resources\RestaurantMenuResource\Pages;

use App\Filament\Resources\RestaurantMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRestaurantMenu extends EditRecord
{
    protected static string $resource = RestaurantMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

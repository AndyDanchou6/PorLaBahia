<?php

namespace App\Filament\Resources\RestaurantMenuPageResource\Pages;

use App\Filament\Resources\RestaurantMenuPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRestaurantMenuPage extends EditRecord
{
    protected static string $resource = RestaurantMenuPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

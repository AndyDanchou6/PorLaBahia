<?php

namespace App\Filament\Resources\AboutUsResource\Pages;

use App\Filament\Resources\AboutUsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAboutUs extends EditRecord
{
    protected static string $resource = AboutUsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('back')
                ->url(AboutUsResource::getUrl())
                ->button()
                ->color('gray'),
        ];
    }
}
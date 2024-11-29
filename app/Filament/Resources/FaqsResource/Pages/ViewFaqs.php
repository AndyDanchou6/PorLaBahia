<?php

namespace App\Filament\Resources\FaqsResource\Pages;

use App\Filament\Resources\FaqsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFaqs extends ViewRecord
{
    protected static string $resource = FaqsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('warning'),
            Actions\Action::make('back')
                ->url(FaqsResource::getUrl())
                ->button()
                ->color('gray'),
        ];
    }
}

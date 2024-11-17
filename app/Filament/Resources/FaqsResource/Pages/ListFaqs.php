<?php

namespace App\Filament\Resources\FaqsResource\Pages;

use App\Filament\Resources\FaqsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Faqs;

class ListFaqs extends ListRecords
{
    protected static string $resource = FaqsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New FAQS'),
        ];
    }
}

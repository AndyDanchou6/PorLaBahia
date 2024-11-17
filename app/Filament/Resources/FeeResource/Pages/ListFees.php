<?php

namespace App\Filament\Resources\FeeResource\Pages;

use App\Filament\Resources\FeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Fee;

class ListFees extends ListRecords
{
    protected static string $resource = FeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // public function getTabs(): array
    // {
    //     return [
    //         'all' => Tab::make('All')
    //             ->badge(Fee::whereNull('deleted_at')->count())
    //             ->modifyQueryUsing(fn(Builder $query) => $query->whereNull('deleted_at')),
    //         'archived' => Tab::make('Archived')
    //             ->badge(Fee::onlyTrashed()->count())
    //             ->modifyQueryUsing(fn(Builder $query) => $query->onlyTrashed()),
    //     ];
    // }
}

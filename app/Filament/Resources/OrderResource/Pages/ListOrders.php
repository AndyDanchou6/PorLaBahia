<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Order;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

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
    //             ->badge(Order::whereNull('deleted_at')->count())
    //             ->modifyQueryUsing(fn(Builder $query) => $query->whereNull('deleted_at')),
    //         'archived' => Tab::make('Archived')
    //             ->badge(Order::onlyTrashed()->count())
    //             ->modifyQueryUsing(fn(Builder $query) => $query->onlyTrashed()),
    //     ];
    // }
}

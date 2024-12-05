<?php

namespace App\Filament\Resources\AccommodationPromoResource\Pages;

use App\Filament\Resources\AccommodationPromoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\AccommodationPromo;

class ListAccommodationPromos extends ListRecords
{
    protected static string $resource = AccommodationPromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            // ->after(fn($action) => $this->limitCreateAction()),
        ];
    }

    // public static function limitCreateAction()
    // {
    //     $accommodationId = request()->get('accommodation_id');
    //     $existingPromo = AccommodationPromo::where('accommodation_id', $accommodationId)->first();

    //     if ($existingPromo) {
    //         return [
    //             'disabled' => true,
    //             'message' => 'A promo for this accommodation already exists.',
    //         ];
    //     }

    //     return [];
    // }
}

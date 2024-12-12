<?php

namespace App\Http\Controllers;

use App\Models\Amenities;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    public function getAmenities()
{
    $amenities = Amenities::all();

    $amenities->map(function ($amenity) {
        $amenity->main_image = asset('storage/' . $amenity->main_image);
        return $amenity;
    });

    return response()->json($amenities);
}
}

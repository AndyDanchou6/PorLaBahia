<?php

namespace App\Http\Controllers;

use App\Models\Amenities;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    public function getAmenities()
    {
        $amenities = Amenities::all();
        return response()->json($amenities);
    }
}

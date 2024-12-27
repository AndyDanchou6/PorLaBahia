<?php

namespace App\Http\Controllers;

use App\Models\Amenities;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    public function getAmenities()
    {
        $amenities = Amenities::with('galleries')->get();
        return response()->json($amenities);
    }

    public function viewAmenities()
    {
        return view('amenities');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{
    public function getAccommodations()
    {
        $accommodations = Accommodation::all();
        return response()->json($accommodations);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{
    public function getAccommodations()
    {
        $accommodations = Accommodation::all();
        $accommodations->map(function($accommodation){
            $accommodation->main_image = asset('storage/'. $accommodation->main_image);
            return $accommodation;
        });
        return response()->json($accommodations);
    }
}

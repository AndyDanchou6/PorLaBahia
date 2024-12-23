<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{
    public function viewAccommodations()
    {
        return view('accommodation');
    }
    public function getAccommodations()
    {
        $accommodations = Accommodation::with('galleries')->get();
        return response()->json($accommodations);
    }
}

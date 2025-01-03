<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;

class AccommodationController extends Controller
{
    public function viewAccommodations()
    {
        return view('accommodation');
    }

    public function getAccommodations()
    {
        $accommodations = Accommodation::with('galleries')->get();

        $convertTools = new CommonMarkConverter();

        $accommodations->transform(function ($accommodation) use ($convertTools) {
            $accommodation['description'] = nl2br($accommodation['description']);
            $accommodation['description'] = $convertTools->convert($accommodation['description'])->getContent();
            return $accommodation;
        });

        return response()->json($accommodations);
    }
}

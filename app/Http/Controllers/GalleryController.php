<?php

namespace App\Http\Controllers;

use App\Models\Amenities;
use App\Models\Galleries;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function getGalleries()
    {
        $galleries = Galleries::all();
        return response()->json($galleries);
    }
}

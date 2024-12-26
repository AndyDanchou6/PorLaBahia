<?php

namespace App\Http\Controllers;

use App\Models\Galleries;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function getFeaturedImages()
    {
        $featuredImages = Galleries::where('is_featured',true)->get();
        return response()->json($featuredImages);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ContentManagementSystem;
use Illuminate\Http\Request;

class ContentManagementController extends Controller
{
    public function getAbout()
    {
        $getAboutPage = ContentManagementSystem::where('page', 'about')->where('is_published', 1)->get();

        // return response()->json($getAboutPage);
        // dd($getAboutPage);
        return response()->json([
            'data' => $getAboutPage,
            'message' => 'Fetched Success',
        ]);
    }  
    
    public function getFirstSection()
    {
        $home = ContentManagementSystem::all();
        return response()->json($home);
    }
}

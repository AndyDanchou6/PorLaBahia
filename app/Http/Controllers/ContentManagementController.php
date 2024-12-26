<?php

namespace App\Http\Controllers;

use App\Models\ContentManagementSystem;
use Illuminate\Http\Request;

class ContentManagementController extends Controller
{
    public function getContents()
    {
        $home = ContentManagementSystem::all();
        return response()->json($home);
    }
}

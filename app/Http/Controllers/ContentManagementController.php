<?php

namespace App\Http\Controllers;

use App\Models\ContentManagementSystem;
use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;

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

    public function getContents()
    {
        $home = ContentManagementSystem::all();

        $convertTools = new CommonMarkConverter();

        $home->transform(function ($description) use ($convertTools) {
            $description['value'] = nl2br($description['value']);
            $description['value'] = $convertTools->convert($description['value'])->getContent();
            return $description;
        });

        return response()->json($home);
    }
}

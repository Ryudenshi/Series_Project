<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Episode;

class EpisodeController extends Controller
{
    public function index($seasonId)
    {
        return Episode::where('season_id', $seasonId)->get();
    }

    public function uploadVideo(Request $request)
{
    $request->validate([
        'video' => 'required|mimes:mp4|max:50000', // Обмеження на тип і розмір
    ]);

    $path = $request->file('video')->store('videos', 's3');

    return response()->json(['path' => $path]);
}

}

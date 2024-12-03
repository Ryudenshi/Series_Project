<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Season;

class SeasonController extends Controller
{
    public function index($seriesId)
    {
        return Season::where('series_id', $seriesId)->with('episodes')->get();
    }

    public function store(Request $request, $seriesId)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'season_number' => 'required|integer|min:1',
        ]);

        $data['series_id'] = $seriesId;

        $season = Season::create($data);

        return response()->json($season, 201);
    }
}

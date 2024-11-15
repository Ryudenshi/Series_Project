<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    public function index($seriesId)
    {
        return Season::where('series_id', $seriesId)->with('episodes')->get();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function index($seasonId)
    {
        return Episode::where('season_id', $seasonId)->get();
    }
}

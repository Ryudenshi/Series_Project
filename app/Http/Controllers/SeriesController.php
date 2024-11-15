<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index()
    {
        return Series::with('seasons.episodes')->get();
    }

    public function show($id)
    {
        $series = Series::with('seasons.episodes')->find($id);

        if (!$series) {
            return response()->json(['message' => 'Series not found'], 404);
        }

        return $series;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'poster' => 'nullable|string',
        ]);

        $series = Series::create($data);
        return response()->json($series, 201);
    }

    public function update(Request $request, $id)
    {
        $series = Series::find($id);

        if (!$series) {
            return response()->json(['message' => 'Series not found'], 404);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'poster' => 'nullable|string',
        ]);

        $series->update($data);
        return $series;
    }

    public function destroy($id)
    {
        $series = Series::find($id);

        if (!$series) {
            return response()->json(['message' => 'Series not found'], 404);
        }

        $series->delete();
        return response()->json(['message' => 'Series deleted']);
    }
}

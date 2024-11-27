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
    try {
        // Валідуємо дані
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Зберігаємо зображення, якщо воно є
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
            $data['poster'] = $posterPath;
        }

        // Створюємо новий серіал
        $series = Series::create($data);

        // Повертаємо відповідь з новим серіалом
        return response()->json($series, 201);
    } catch (\Exception $e) {
        // Логування помилки
        \Log::error('Error creating series: ' . $e->getMessage());

        // Повертаємо повідомлення про помилку
        return response()->json(['error' => 'Не вдалося створити серіал.'], 500);
    }
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

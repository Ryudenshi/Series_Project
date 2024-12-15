<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Episode;
use App\Models\Series;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EpisodeController extends Controller
{
    public function index($seasonId)
    {
        Log::info('Season ID:', ['id' => $seasonId]);
        $episodes = Episode::where('season_id', $seasonId)->get();
        return response()->json($episodes);
    }

    public function show($id)
    {
        $series = Series::with('seasons')->findOrFail($id);
        return response()->json($series);
    }

    public function uploadVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:mp4|max:50000', // Обмеження на тип і розмір
        ]);

        // Отримуємо оригінальну назву файлу
        $fileName = $request->file('video')->getClientOriginalName();

        // Завантажуємо файл на S3 без додаткових папок
        Storage::disk('s3')->put($fileName, file_get_contents($request->file('video')));

        // Формуємо абсолютний URL
        $url = 'https://' . env('AWS_BUCKET') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . $fileName;

        return response()->json(['path' => $url]); // Повертаємо URL
    }



    public function store(Request $request, $seasonId)
    {

        Log::info('Дані для створення епізоду:', $request->all());
        // Валідація даних
        $request->validate([
            'title' => 'required|string|max:255',
            'episode_number' => 'required|integer',
            'video_url' => 'required|string',
        ]);

        // Створення епізоду
        $episode = new Episode();
        $episode->title = $request->title;
        $episode->episode_number = $request->episode_number;
        $episode->video_url = $request->video_url;
        $episode->season_id = $seasonId; // Призначаємо сезон, з якого цей епізод
        $episode->save();

        // Повернення відповіді
        return response()->json($episode, 201); // Створення успішно
    }
}

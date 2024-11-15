<?php

namespace Database\Seeders;

use App\Models\Series;
use App\Models\Season;
use App\Models\Episode;
use Illuminate\Database\Seeder;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $series = Series::create([
            'title' => 'Example Series',
            'description' => 'This is an example series description.',
            'poster' => '/path/to/poster.jpg',
        ]);

        $season = $series->seasons()->create([
            'title' => 'Season 1',
            'season_number' => 1,
        ]);

        $season->episodes()->createMany([
            ['title' => 'Episode 1', 'episode_number' => 1, 'video_url' => '/videos/episode1.mp4'],
            ['title' => 'Episode 2', 'episode_number' => 2, 'video_url' => '/videos/episode2.mp4'],
        ]);
    }

}

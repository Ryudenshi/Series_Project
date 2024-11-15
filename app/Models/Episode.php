<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;

    protected $fillable = [
        'season_id',
        'title',
        'episode_number',
        'video_url',
    ];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }
}

<?php

use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\EpisodeController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/series', [SeriesController::class, 'index']);
    Route::get('/series/{id}', [SeriesController::class, 'show']);
    Route::post('/series', [SeriesController::class, 'store']);
    Route::put('/series/{id}', [SeriesController::class, 'update']);
    Route::delete('/series/{id}', [SeriesController::class, 'destroy']);
});
Route::get('/series/{seriesId}/seasons', [SeasonController::class, 'index']);
Route::get('/seasons/{seasonId}/episodes', [EpisodeController::class, 'index']);

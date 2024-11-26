<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/series', [SeriesController::class, 'index']);
    Route::get('/series/{id}', [SeriesController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/series', [SeriesController::class, 'store']);
    Route::put('/series/{id}', [SeriesController::class, 'update']);
    Route::delete('/series/{id}', [SeriesController::class, 'destroy']);
});

Route::get('/series/{seriesId}/seasons', [SeasonController::class, 'index']);
Route::get('/seasons/{seasonId}/episodes', [EpisodeController::class, 'index']);
Route::post('/upload-video', [EpisodeController::class, 'uploadVideo']);

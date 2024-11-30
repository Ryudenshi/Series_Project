<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Filesystem\FilesystemServiceProvider;

Route::get('/login', function () {
    return response()->json(['message' => 'Unauthorized'], 401);
})->name('api.login');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/series', [SeriesController::class, 'index']);
Route::get('/series/{id}', [SeriesController::class, 'show']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/series', [SeriesController::class, 'store']);
    Route::put('/series/{id}', [SeriesController::class, 'update']);
    Route::delete('/series/{id}', [SeriesController::class, 'destroy']);
    Route::post('/series/{seriesId}/seasons', [SeasonController::class, 'store']);
    Route::post('/seasons/{seasonId}/episodes', [EpisodeController::class, 'store']);
});

Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/series/{seriesId}/seasons', [SeasonController::class, 'index']);
Route::get('/series/{seriesId}/seasons', [SeasonController::class, 'index']);
Route::get('/seasons/{seasonId}/episodes', [EpisodeController::class, 'index']);
Route::post('/upload-video', [EpisodeController::class, 'uploadVideo']);

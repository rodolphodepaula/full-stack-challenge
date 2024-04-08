<?php

use App\Http\Controllers\Api\TrackController;
use Illuminate\Support\Facades\Route;


Route::get('/tracks/search/{isrc}', [TrackController::class, 'search'])->name('track.search');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tracks', TrackController::class);
});


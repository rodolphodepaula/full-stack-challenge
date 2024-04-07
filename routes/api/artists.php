<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArtistController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('artists', ArtistController::class)
        ->only(['index', 'show', 'store', 'update', 'destroy']);
});

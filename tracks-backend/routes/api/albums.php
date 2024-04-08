<?php

use App\Http\Controllers\Api\AlbumController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('albums', AlbumController::class)
        ->only(['index', 'show', 'store', 'update', 'destroy']);
});

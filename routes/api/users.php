<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('users', UserController::class)
        ->only(['index', 'show', 'store', 'update', 'destroy']);
});

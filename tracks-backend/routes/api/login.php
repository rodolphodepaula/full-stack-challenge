<?php

use App\Http\Controllers\Api\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/login',[LoginController::class, 'login']);

Route::middleware('auth:sanctum')
    ->post('/logout', [LoginController::class, 'logout']);


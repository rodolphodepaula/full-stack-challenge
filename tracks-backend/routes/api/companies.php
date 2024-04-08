<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('companies', CompanyController::class)
        ->only(['index', 'show', 'store', 'update', 'destroy']);
});

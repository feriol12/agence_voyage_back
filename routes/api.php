<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\DestinationController;

// Routes accessibles uniquement aux admins
// Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
//     Route::apiResource('destinations', DestinationController::class);
// });

// Routes accessibles à tous (lecture seule)
Route::get('destinations', [DestinationController::class, 'index']);
Route::get('destinations/{id}', [DestinationController::class, 'show']);

Route::prefix('admin')->group(function () {
    Route::apiResource('destinations', DestinationController::class);
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\DestinationController;

// Routes accessibles uniquement aux admins
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::apiResource('destinations', DestinationController::class);
});

// Routes accessibles à tous (lecture seule)
Route::get('destinations', [DestinationController::class, 'index']);
Route::get('destinations/{id}', [DestinationController::class, 'show']);

// Route::prefix('admin')->group(function () {
//     Route::apiResource('destinations', DestinationController::class);
// });
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetController;
use Illuminate\Support\Facades\Mail;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Routes publiques
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});



Route::get('/test-mail', function () {
    try {
        Mail::raw('Test de réinitialisation', function ($message) {
            $message->to('test@example.com')
                    ->subject('Test Mail');
        });
        return response()->json(['message' => 'Email envoyé avec succès!']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

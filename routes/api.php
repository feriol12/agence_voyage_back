<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\DestinationController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\TripController;

// Routes DESTINATIONS
Route::prefix('admin')->group(function () {
    Route::get('destinations', [DestinationController::class, 'index']);
    Route::get('destinations/{id}', [DestinationController::class, 'show']);
    Route::post('destinations', [DestinationController::class, 'store']);
    Route::put('destinations/{id}', [DestinationController::class, 'update']);
    Route::delete('destinations/{id}', [DestinationController::class, 'destroy']);
});

// Routes publiques (lecture seule)
Route::get('destinations', [DestinationController::class, 'index']);
Route::get('destinations/{id}', [DestinationController::class, 'show']);

// Routes AUTHENTIFICATION
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

// ==================== ROUTES PROTÉGÉES (AUTH + TOKEN) ====================
Route::middleware('auth:sanctum')->group(function () {

    // Routes utilisateur de base
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // ==================== ROUTES ADMIN UNIQUEMENT ====================
    Route::middleware('admin')->prefix('admin')->group(function () {  // ← 'admin' au lieu de 'role:admin'

        // Stats admin
        Route::get('/stats/users', [StatsController::class, 'getUsersStats']);

        // Gestion des destinations
        Route::apiResource('destinations', DestinationController::class);

        // Gestion des voyages
        Route::apiResource('trips', TripController::class);
    });
});

// Test mail
Route::get('/test-mail', function () {
    try {
        Mail::raw('Test de réinitialisation', function ($message) {
            $message->to('test@example.com')->subject('Test Mail');
        });
        return response()->json(['message' => 'Email envoyé avec succès!']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

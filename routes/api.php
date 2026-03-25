<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\MatchController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('role:organizer')->group(function () {
        Route::post('/tournaments', [TournamentController::class, 'store']);
        Route::put('/tournaments/{tournament}', [TournamentController::class, 'update']);
        Route::delete('/tournaments/{tournament}', [TournamentController::class, 'destroy']);
    });

    Route::middleware('role:player')->group(function () {});

    Route::post('/tournaments/{tournament}/register', [RegistrationController::class, 'store']);
    Route::post('/tournaments/{tournament}/close', [StatusController::class, 'close']);
    Route::post('/matches/{match}/score', [MatchController::class, 'submitScore']);
});

Route::get('/tournaments', [TournamentController::class, 'index']);
Route::get('/tournaments/{tournament}', [TournamentController::class, 'show']);

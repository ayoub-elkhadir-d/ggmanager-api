<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\MatchController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/tournaments', [TournamentController::class, 'index']);
Route::get('/tournaments/{tournament}', [TournamentController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);

    Route::post('/tournaments', [TournamentController::class, 'store']);
    Route::put('/tournaments/{tournament}', [TournamentController::class, 'update']);
    Route::delete('/tournaments/{tournament}', [TournamentController::class, 'destroy']);

    Route::post('/tournaments/{tournament}/register', [RegistrationController::class, 'store']);
    Route::post('/tournaments/{tournament}/close', [StatusController::class, 'close']);
    Route::post('/matches/{match}/score', [MatchController::class, 'submitScore']);
});

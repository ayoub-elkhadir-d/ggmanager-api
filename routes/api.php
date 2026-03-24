<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TournamentController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::apiResource('tournament', TournamentController::class);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('role:organizer')->group(function () {
        Route::post('/tournaments', [App\Http\Controllers\TournamentController::class, 'store']);
        Route::put('/tournaments/{tournament}', [App\Http\Controllers\TournamentController::class, 'update']);
        Route::delete('/tournaments/{tournament}', [App\Http\Controllers\TournamentController::class, 'destroy']);
    });

    Route::middleware('role:player')->group(function () {});
});

Route::get('/tournaments', [App\Http\Controllers\TournamentController::class, 'index']);
Route::get('/tournaments/{tournament}', [App\Http\Controllers\TournamentController::class, 'show']);

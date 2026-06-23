<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\HistorialPdfController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RondaController;
use Illuminate\Support\Facades\Route;

Route::post('login', [LoginController::class, 'login'])->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [LoginController::class, 'user']);
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::get('/rooms', [RoomController::class, 'index']);
    Route::get('/rooms/{room}', [RoomController::class, 'show']);

    Route::get('/rondas/today', [RondaController::class, 'getOrCreateToday']);
    Route::post('/rondas/{ronda}/rooms/{room}/state', [RondaController::class, 'updateRoomState']);
    Route::post('/rondas/{ronda}/finalize', [RondaController::class, 'finalize']);
    Route::post('/rondas/{ronda}/reopen', [RondaController::class, 'reopen']);

    Route::get('/historial/pdf', [HistorialPdfController::class, 'download']);
    Route::get('/historial', [HistorialController::class, 'index']);
    Route::put('/historial/{historial}', [HistorialController::class, 'update']);
    Route::delete('/historial/{historial}', [HistorialController::class, 'destroy']);
});

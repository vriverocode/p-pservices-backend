<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rutas protegidas por Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Devuelve los datos del usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Aquí irán las rutas de tu lógica de negocio
    
    Route::post('/logout', [AuthController::class, 'logout']);
});
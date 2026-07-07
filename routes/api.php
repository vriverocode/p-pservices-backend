<?php

use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\Admin\VehicleTypeController;
use App\Http\Controllers\Api\Admin\VehicleMakeController;
use App\Http\Controllers\Api\Admin\VehicleModelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/email/verify/{id}/{hash}', [UserController::class, 'verifyEmail'])->name('verification.verify');
Route::post('/email/resend', [UserController::class, 'resendVerificationEmail']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'getCurrentUser']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('services')->group(function () {
        Route::get('/', [ServicesController::class, 'index']);
    });

    Route::prefix('ads')->group(function () {
        Route::get('/', [AdController::class, 'index']);
    });

    Route::apiResource('vehicles', VehicleController::class);
    Route::patch('vehicles/{vehicle}/primary', [VehicleController::class, 'setPrimary']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::apiResource('vehicle-types', VehicleTypeController::class);
    Route::apiResource('vehicle-makes', VehicleMakeController::class);
    Route::apiResource('vehicle-models', VehicleModelController::class);
});

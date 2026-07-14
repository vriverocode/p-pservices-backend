<?php

use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VehicleCategoryController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\Admin\ServicesController as AdminServicesController;
use App\Http\Controllers\Api\Admin\ServicePricingController;
use App\Http\Controllers\Api\Admin\WorkspaceController;
use App\Http\Controllers\Api\Admin\WorkspaceScheduleController;
use App\Http\Controllers\Api\Admin\VehicleTypeController;
use App\Http\Controllers\Api\Admin\VehicleMakeController;
use App\Http\Controllers\Api\Admin\VehicleModelController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:forgot-password');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:reset-password');
Route::get('/email/verify/{id}/{hash}', [UserController::class, 'verifyEmail'])->name('verification.verify');
Route::post('/email/resend', [UserController::class, 'resendVerificationEmail']);

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/user', [AuthController::class, 'getCurrentUser']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('services')->group(function () {
        Route::get('/', [ServicesController::class, 'index']);
        Route::get('{service}', [ServicesController::class, 'show']);
        Route::get('{service}/price', [ServicesController::class, 'price']);
    });

    Route::get('/vehicle-categories', [VehicleCategoryController::class, 'index']);

    Route::prefix('ads')->group(function () {
        Route::get('/', [AdController::class, 'index']);
    });

    Route::apiResource('vehicles', VehicleController::class);
    Route::patch('vehicles/{vehicle}/primary', [VehicleController::class, 'setPrimary']);
    Route::get('/appointments/slots', [AppointmentController::class, 'slots']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::apiResource('services', AdminServicesController::class);
    Route::apiResource('services.pricing', ServicePricingController::class);
    Route::apiResource('vehicle-types', VehicleTypeController::class);
    Route::apiResource('vehicle-makes', VehicleMakeController::class);
    Route::apiResource('vehicle-models', VehicleModelController::class);
    Route::apiResource('workspaces', WorkspaceController::class);
    Route::apiResource('workspaces.schedules', WorkspaceScheduleController::class);
});

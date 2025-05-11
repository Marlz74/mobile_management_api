<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('/devices/register', [DeviceController::class, 'register']);
        Route::post('/devices/{device_id}/lock', [DeviceController::class, 'lock']);
        Route::post('/devices/{device_id}/unlock', [DeviceController::class, 'unlock']);
    });

});
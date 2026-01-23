<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Driver\VehicleController;
use App\Http\Controllers\Api\V1\Admin\AdminDriverController;
use App\Http\Controllers\Api\V1\Driver\DriverRequestController;


Route::middleware(['auth:sanctum', 'role:passenger'])->group(function () {
    Route::post('/driver/request', [DriverRequestController::class, 'store']);
});
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/driver-requests', [AdminDriverController::class, 'index']);
    Route::post('/admin/driver-requests/{id}/approve', [AdminDriverController::class, 'approve']);
    Route::post('/admin/driver-requests/{id}/reject', [AdminDriverController::class, 'reject']);

    Route::post('/vehicles', [VehicleController::class, 'store']);
    Route::get('/vehicles', [VehicleController::class, 'index']);
});

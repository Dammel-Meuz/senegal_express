<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\OtpController;
use App\Http\Controllers\Api\V1\Auth\AuthController;


Route::post('auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/moi', [AuthController::class, 'me']);

    // otp verify num
    Route::post('/auth/otp/send', [OtpController::class, 'send']);
    Route::post('/auth/otp/verify', [OtpController::class, 'verify']);

});
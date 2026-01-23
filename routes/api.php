<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;



Route::prefix('v1')->group(function () {
    require __DIR__.'/api/v1/auth.php';
    require __DIR__.'/api/v1/driver.php';
});

Route::middleware('throttle:api')->group(function () {
    // routes API
});


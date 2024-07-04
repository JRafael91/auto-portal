<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;


Route::prefix('v1')->group(function () {

    // Auth
    Route::post('login', [ AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [ AuthController::class, 'logout']);
        
        Route::get('orders', [ OrderController::class, 'index']);
        Route::get('orders/technic', [ OrderController::class, 'ordersByTechnic']);
        Route::get('orders/{uid}', [ OrderController::class, 'show']);
    });

});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\WarehouseController;

// Public route
Route::post('/login', [AuthController::class, 'login']);

// Protected routes with Sanctum middleware
Route::middleware('auth:sanctum')->group(function () {

   Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/pricing', [ProductController::class, 'pricing']);
        Route::get('{id}/price', [ProductController::class, 'getPrice']);
    });

    Route::post('/stock', [StockController::class, 'store']);
    Route::get('/warehouses/{id}/report', [WarehouseController::class, 'report']);
});


      

});


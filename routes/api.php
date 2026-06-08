<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SystemSettingController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('/services')->group(function () {
        Route::get('/', [ServiceController::class, 'index']);
        Route::get('/create', [ServiceController::class, 'store']);
    });
    Route::prefix('/resources')->group(function () {
        Route::get('/', [ResourcesController::class, 'index']);
        Route::get('/create', [ResourcesController::class, 'store']);
    });
    Route::prefix('/booking')->group(function () {
        Route::get('/', [BookingController::class, 'index']);
        Route::get('/create', [BookingController::class, 'store']);
        Route::patch('/update', [BookingController::class, 'update']);
        Route::patch('/{booking}/status', [BookingController::class, 'updateStatus']);
        Route::get('/history', [BookingController::class, 'search']);
    });
    Route::prefix('/system-setting')->group(function () {
        Route::get('/', [SystemSettingController::class, 'index']);
        Route::get('/create', [SystemSettingController::class, 'store']);
        Route::patch('/update', [SystemSettingController::class, 'update']);
    });
    Route::prefix('/products')->group(function () {
       Route::get('/', [ProductController::class, 'index']);
       Route::get('/create', [ProductController::class, 'store']);
       Route::patch('/update', [ProductController::class, 'update']);
       Route::get('{product}', [ProductController::class, 'show']);
       Route::patch('/products/{product}/stock', [ProductController::class, 'updateStock']);
       Route::get('/products/low-stock', [ProductController::class, 'lowStock']);
    });
//});

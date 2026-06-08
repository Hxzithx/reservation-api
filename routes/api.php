<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SystemSettingController;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('/services')->group(function () {
        Route::get('/', [ServiceController::class, 'index']);
        Route::post('/create', [ServiceController::class, 'store']);
    });
    Route::prefix('/resources')->group(function () {
        Route::get('/', [ResourcesController::class, 'index']);
        Route::post('/create', [ResourcesController::class, 'store']);
    });
    Route::prefix('/booking')->group(function () {
        Route::get('/', [BookingController::class, 'index']);
        Route::post('/create', [BookingController::class, 'store']);
        Route::patch('/update', [BookingController::class, 'update']);
        Route::patch('/{booking}/status', [BookingController::class, 'updateStatus']);
        Route::get('/history', [BookingController::class, 'search']);
    });
    Route::prefix('/system-setting')->group(function () {
        Route::get('/', [SystemSettingController::class, 'index']);
        Route::post('/create', [SystemSettingController::class, 'store']);
        Route::patch('/update', [SystemSettingController::class, 'update']);
    });
    Route::prefix('/products')->group(function () {
       Route::get('/', [ProductController::class, 'index']);
       Route::get('/create', [ProductController::class, 'store']);
       Route::patch('/update', [ProductController::class, 'update']);
       Route::get('{product}', [ProductController::class, 'show']);
       Route::patch('/{product}/stock', [ProductController::class, 'updateStock']);
       Route::get('/low-stock', [ProductController::class, 'lowStock']);
    });
    Route::prefix('/order-item')->group(function () {
       Route::get('/', [OrderItemController::class,  'index']);
       Route::post('/create', [OrderItemController::class, 'store']);
       Route::get('/{orderItem}', [OrderItemController::class, 'show']);
       Route::patch('/update', [OrderItemController::class, 'update']);
       Route::get('/booking/{booking}', [OrderItemController::class, 'byBooking']); //get order by booking
       Route::patch('/{orderItem}/status', [OrderItemController::class, 'updateStatus']);
    });
//});

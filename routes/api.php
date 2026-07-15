<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SystemSettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::middleware('auth:sanctum')->group(function () {

Route::prefix('/services')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);
    Route::post('/', [ServiceController::class, 'store']);
    Route::patch('/{service}', [ServiceController::class, 'update']);
});
Route::prefix('/resources')->group(function () {
    Route::get('/', [ResourcesController::class, 'index']);
    Route::post('/', [ResourcesController::class, 'store']);
    Route::patch('/{resource}', [ResourcesController::class, 'update']);
});
Route::prefix('/booking')->group(function () {
    Route::get('/', [BookingController::class, 'index']);
    Route::post('/', [BookingController::class, 'store']);
    Route::patch('/{booking}', [BookingController::class, 'update']);
    Route::patch('/{booking}/status', [BookingController::class, 'updateStatus']);
    Route::get('/history', [BookingController::class, 'search']);
});
Route::prefix('/system-setting')->group(function () {
    Route::get('/', [SystemSettingController::class, 'index']);
    Route::post('/', [SystemSettingController::class, 'store']);
    Route::patch('/{systemSetting}', [SystemSettingController::class, 'update']);
});
Route::prefix('/products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/', [ProductController::class, 'store']);
    Route::patch('/{product}', [ProductController::class, 'update']);
    Route::get('/{product}', [ProductController::class, 'show']);
    Route::patch('/{product}/stock', [ProductController::class, 'updateStock']);
    Route::get('/low-stock', [ProductController::class, 'lowStock']);
});
Route::prefix('/order-item')->group(function () {
    Route::get('/', [OrderItemController::class,  'index']);
    Route::post('/create', [OrderItemController::class, 'store']);
    Route::get('/{orderItem}', [OrderItemController::class, 'show']);
    Route::patch('/update', [OrderItemController::class, 'update']);
    Route::get('/booking/{booking}', [OrderItemController::class, 'byBooking']); //get order by bookingId
    Route::patch('/{orderItem}/status', [OrderItemController::class, 'updateStatus']);
});
Route::prefix('/package')->group(function () {
    Route::get('/', [PackageController::class, 'index']);
    Route::post('/', [PackageController::class, 'store']);
    Route::get('/{package}', [PackageController::class, 'show']);
    Route::patch('/{package}', [PackageController::class, 'update']);
    Route::get('/active', [PackageController::class, 'active']);
    Route::delete('/{package}', [PackageController::class, 'destroy']);
});
//});

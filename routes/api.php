<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

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
       Route::get('/create', [BookingController::class, 'create']);
       Route::patch('/update', [BookingController::class, 'update']);
       Route::patch('/{booking}/status', [BookingController::class, 'updateStatus']); 
       Route::get('/history', [BookingController::class, 'search']);
    });
});

<?php

use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/services')->group(function () {
   Route::get('/', [ServiceController::class, 'index']);
   Route::get('/create', [ServiceController::class, 'store']);
});


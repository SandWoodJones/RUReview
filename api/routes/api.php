<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MealController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/meals', [MealController::class, 'index']);
Route::get('/meals/{meal}', [MealController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::middleware('admin')->group(function () {
        Route::post('/meals', [MealController::class, 'store']);
        Route::put('/meals/{meal}', [MealController::class, 'update']);
        Route::delete('/meals/{meal}', [MealController::class, 'destroy']);
    });
});

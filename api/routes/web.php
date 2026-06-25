<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => response()->json([
    'status' => 'success',
    'message' => 'RUReview API',
    'data' => ['version' => '1.0'],
]));

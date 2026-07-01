<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProductController;

Route::apiResource('products', ProductController::class)->middleware('throttle:api');
Route::get('products/low-stock', [ProductController::class, 'lowStock'])->middleware('throttle:api');

Route::post(
    'products/{product}/stock',
    [ProductController::class, 'adjustStock']
);

<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProductController;

Route::apiResource('products', ProductController::class);
Route::get('products/low-stock', [ProductController::class, 'lowStock']);

Route::post(
    'products/{product}/stock',
    [ProductController::class, 'adjustStock']
);

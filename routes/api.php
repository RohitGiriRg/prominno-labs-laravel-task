<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\SellerController;
use App\Http\Controllers\Api\Seller\AuthController as SellerAuthController;
use App\Http\Controllers\Api\Seller\ProductController;



Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/seller/login', [SellerAuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/admin/sellers', [SellerController::class, 'store']);
    Route::get('/admin/sellers', [SellerController::class, 'index']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/seller/products', [ProductController::class, 'store']);
    Route::get('/seller/products', [ProductController::class, 'index']);
    Route::get('/seller/products/{id}/pdf', [ProductController::class, 'pdf']);
    Route::delete('/seller/products/{id}', [ProductController::class, 'destroy']);
});

















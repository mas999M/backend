<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('me', [AuthController::class, 'me']);
Route::get('logout', [AuthController::class, 'logout']);
Route::post('product' , [ProductController::class, 'product']);
Route::get('category' , [ProductController::class, 'category']);
Route::get('products' , [ProductController::class, 'products']);
Route::post('add' , [OrderController::class, 'add']);
Route::get('cart' , [OrderController::class, 'show']);
Route::get('checkout' , [OrderController::class, 'checkout']);
route::get('cartDelete' , [OrderController::class, 'cartDelete'])->name('cartDelete');
Route::get('callback' , [OrderController::class, 'callback'])->name('callback');

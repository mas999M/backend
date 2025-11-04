<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('product' , [ProductController::class, 'product']);
Route::get('category' , [ProductController::class, 'category']);
Route::get('products' , [ProductController::class, 'products']);
Route::post('add' , [OrderController::class, 'add']);
Route::get('cart' , [OrderController::class, 'show']);
Route::get('checkout' , [OrderController::class, 'checkout'])->middleware('auth:sanctum');
Route::get('cartDelete' , [OrderController::class, 'cartDelete'])->name('cartDelete');
Route::get('callback' , [OrderController::class, 'callback'])->name('callback');

Route::get('admin/order' , [AdminController::class, 'order']);
Route::get('admin/users' , [AdminController::class, 'users']);
Route::post('admin/update-users' , [AdminController::class, 'update_users']);
Route::get('admin/orders/{id}' , [AdminController::class, 'orders']);

Route::get('user/orders' , [UserController::class, 'orders']);
Route::post('user/update' , [UserController::class, 'update']);
Route::get('user/orders/{id}' , [AdminController::class, 'userOrders']);

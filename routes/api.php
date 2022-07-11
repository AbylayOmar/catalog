<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

use App\Models\Product;
use App\Models\Cart;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/register',[AuthController::class,'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('/category', CategoryController::class);

Route::apiResource('/product', ProductController::class);
Route::get('/product/slug/{product:slug}', [ProductController::class, 'show']);

Route::apiResource('/cart', CartController::class)->except(['update', 'index']);
Route::apiResource('/order', OrderController::class)->except(['update', 'destroy','store'])->middleware('auth:sanctum');

Route::post('/cart/{cart}', [CartController::class, 'addProducts']);
Route::post('/cart/{cart}/checkout', [CartController::class, 'checkout']);

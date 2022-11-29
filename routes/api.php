<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication
Route::post("/register", "AuthController@register");
Route::post("/login", "AuthController@login");
Route::middleware('auth:sanctum')->post("/logout", "AuthController@logout");

// User Management
Route::resource("/users", "UserController");

// Product Management
Route::resource("/products", "ProductController");

// Order Management
Route::middleware('auth:sanctum')->get("/orders", "OrderController@index");
Route::middleware('auth:sanctum')->post("/orders", "OrderController@store");
Route::middleware('auth:sanctum')->put("/orders/{id}", "OrderController@update");
Route::middleware('auth:sanctum')->delete("/orders/{id}", "OrderController@delete");

Route::middleware('auth:sanctum')->get("/orders/cart", "OrderController@getUserCart");


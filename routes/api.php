<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CatégorieController;
use App\Http\Controllers\MenuItemsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TablesController;
use App\Http\Controllers\TablesReservationController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::middleware('auth:sanctum', RoleMiddleware::class . ':Gérant')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('orders', OrderController::class);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


//payment
Route::apiResource('payment', PaymentController::class)->except(['destroy', 'store']);




//resrervation
Route::apiResource('reservation', TablesReservationController::class)->middleware('auth:sanctum');
Route::put('reservation/cancel/{id}', [TablesReservationController::class, 'cancel']);


//menuItems
Route::apiResource('menuItems' , MenuItemsController::class);



// packs 

Route::apiResource('packs' , PackController::class);



// Categories
 Route::apiResource('categories', CategorieController::class);


 //tables 
Route::apiResource('tables', TablesController::class);


//Salary




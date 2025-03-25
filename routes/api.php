<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::middleware('auth:sanctum' , RoleMiddleware::class . ':Cuisinier')->group( function(){
    Route::post('/logout' , [AuthController::class , 'logout']);
    Route::post('/register' , [AuthController::class , 'register']);
    Route::apiResource('orders' , OrderController::class);
});

Route::post('/login' , [AuthController::class , 'login']);






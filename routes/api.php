<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\TransactionController;

// Endpoint yang bisa diakses publik (Tanpa Login)
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);

// Endpoint yang wajib Login (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('low-stock', [ProductController::class, 'lowStock']);
    
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);

    Route::apiResource('products', ProductController::class)->except(['index', 'show', 'lowStock']);

    Route::apiResource('transactions', TransactionController::class)->only(['index', 'show']);

    Route::post('transactions', [TransactionController::class, 'store']);

    Route::get('dashboard', [DashboardController::class, 'index']);
    
    // Endpoint logout
    Route::post('logout', [AuthController::class, 'logout']);
});

// Endpoint Login
Route::post('login', [AuthController::class, 'login']);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/admin-login', [AuthController::class, 'adminLogin'])->name('login.admin');

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);


Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user-profile', [AuthController::class, 'user']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Add category – only admin
    Route::post('/categories', [CategoryController::class, 'store']);

    // Add posts
    Route::post('/posts', [PostController::class, 'store']);

});
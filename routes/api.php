<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReplyController;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/admin-login', [AuthController::class, 'adminLogin'])->name('login.admin');

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/posts/{post}/replies', [ReplyController::class, 'index']);

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

    // Add replies
    Route::post('/posts/{post}/replies', [ReplyController::class, 'store']);


});
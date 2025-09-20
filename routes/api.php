<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\Admin\UserRoleController;

// Public routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/admin-login', [AuthController::class, 'adminLogin'])->name('login.admin');

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}/replies', [ReplyController::class, 'index']);

// Routes protected by authentication
Route::middleware('auth:api')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user-profile', [AuthController::class, 'user']);

    // Admin-only: Add category
    Route::post('/categories', [CategoryController::class, 'store']);

    // Posts
    Route::post('/posts', [PostController::class, 'store']);
    Route::patch('/posts/{post}/approve', [PostController::class, 'approve']);

    // Replies
    Route::post('/posts/{post}/replies', [ReplyController::class, 'store']);

    // Admin: Toggle moderator role
    Route::patch('/admin/users/{user}/toggle-moderator', [UserRoleController::class, 'toggleModerator'])
        ->name('admin.users.toggleModerator');
});
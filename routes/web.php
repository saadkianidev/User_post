<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('index');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/all', [PostController::class, 'allPosts'])->name('posts.allposts');
    Route::get('/posts/grid', [PostController::class, 'grid'])->name('posts.grid');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');

    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

    Route::get('/settings', [IndexController::class, 'settings'])->name('settings');
    Route::post('/settings', [IndexController::class, 'updateSettings'])->name('settings.update');

    Route::post('/theme/update', [ThemeController::class, 'update'])->name('theme.update');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::middleware(['auth'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/message', [UserController::class, 'sendMessage'])->name('users.message');
    });
});

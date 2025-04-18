<?php

use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/chat/{uuid?}', [ChatBotController::class, 'chat'])->name('chat.chat');
    Route::post('/chat', [ChatBotController::class, 'store'])->name('chat.store');
    Route::post('/chat/{chat_id}/message', [ChatBotController::class, 'chatApi'])->name('chat.message');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('/users', UserController::class)->names('admin.users');
    Route::resource('/posts', PostController::class)->names('admin.posts');
});

require __DIR__ . '/auth.php';

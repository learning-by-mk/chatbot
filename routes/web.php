<?php

use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::middleware(['api', 'auth:sanctum'])->prefix('api/chat')->group(function () {
    // Route::get('/{uuid?}', [ChatBotController::class, 'chat'])->name('chat.chat');
    Route::post('/', [ChatBotController::class, 'store'])->name('chat.store');
    Route::post('/{chat_id}/message', [ChatBotController::class, 'chatApi'])->name('chat.message');
});

Route::middleware(['api', 'auth:sanctum'])->prefix('api')->group(function () {
    // Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::apiResource('/users', UserController::class)->names('admin.users');
    Route::apiResource('/posts', PostController::class)->names('admin.posts');
});

require __DIR__ . '/auth.php';

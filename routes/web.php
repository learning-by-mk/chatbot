<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/chat')->group(function () {
    // Route::get('/{uuid?}', [ChatBotController::class, 'chat'])->name('chat.chat');
    Route::post('/', [ChatBotController::class, 'store'])->name('chat.store');
    Route::post('/{chat_id}/message', [ChatBotController::class, 'chatApi'])->name('chat.message');
});

Route::middleware(['api', 'auth:sanctum'])->prefix('api')->group(function () {

    Route::get('user/me', [AuthenticatedSessionController::class, 'me']);
    // Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::apiResource('/users', UserController::class)->names('admin.users');
    Route::apiResource('/posts', PostController::class)->names('admin.posts');
    Route::apiResource('/roles', RoleController::class)->names('admin.roles');
});

require __DIR__ . '/auth.php';

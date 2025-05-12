<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/chat')->group(function () {
    // Route::get('/{uuid?}', [ChatController::class, 'chat'])->name('chat.chat');
    Route::post('/', [ChatController::class, 'store'])->name('chat.store');
    Route::post('/{chat_id}/message', [ChatController::class, 'chatApi'])->name('chat.message');
});

Route::middleware(['api', 'auth:sanctum'])->prefix('api')->group(function () {

    Route::get('user/me', [AuthenticatedSessionController::class, 'me']);
    // Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::apiResource('/users', UserController::class)->names('admin.users');
    Route::apiResource('/roles', RoleController::class)->names('admin.roles');
});

require __DIR__ . '/auth.php';

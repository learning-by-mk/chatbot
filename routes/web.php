<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SettingGroupController;
use App\Http\Controllers\AiSummaryController;
use App\Http\Controllers\ChatbotQuestionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\AiVoiceController;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/chat')->group(function () {
    // Route::get('/{uuid?}', [ChatController::class, 'chat'])->name('chat.chat');
    Route::post('/', [ChatController::class, 'store'])->name('chat.store');
    Route::post('/{chat_id}/message', [ChatController::class, 'chatApi'])->name('chat.message');
});

Route::middleware(['api', 'auth:sanctum'])->prefix('api')->group(function () {

    Route::get('user/me', [AuthenticatedSessionController::class, 'me']);
    // Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::apiResource('/users', UserController::class)->names('admin.users');
    Route::apiResource('/settings', SettingController::class)->names('admin.settings');
    Route::apiResource('/setting-groups', SettingGroupController::class)->names('admin.setting-groups');
    Route::apiResource('/roles', RoleController::class)->names('admin.roles');
    Route::apiResource('/ai-summaries', AiSummaryController::class)->names('admin.ai-summaries');
    Route::apiResource('/chatbot-questions', ChatbotQuestionController::class)->names('admin.chatbot-questions');
    Route::apiResource('/chats', ChatController::class)->names('admin.chats');
    Route::apiResource('/comments', CommentController::class)->names('admin.comments');
    Route::apiResource('/favorites', FavoriteController::class)->names('admin.favorites');
    Route::apiResource('/downloads', DownloadController::class)->names('admin.downloads');
    Route::apiResource('/ai-voices', AiVoiceController::class)->names('admin.ai-voices');
    Route::apiResource('/documents', DocumentController::class)->names('admin.documents');
    Route::apiResource('/categories', CategoryController::class)->names('admin.categories');
});

require __DIR__ . '/auth.php';

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
use App\Http\Controllers\FileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ViewController;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/chat')->group(function () {
    // Route::get('/{uuid?}', [ChatController::class, 'chat'])->name('chat.chat');
    Route::post('/', [ChatController::class, 'store'])->name('chat.store');
    Route::post('/{chat_id}/message', [ChatController::class, 'chatApi'])->name('chat.message');
});

Route::middleware(['api', 'auth:sanctum'])->prefix('api')->group(function () {

    Route::get('user/me', [AuthenticatedSessionController::class, 'me']);
    // Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::apiResource('/users', UserController::class)->names('users');
    Route::get('/users/documents/list', [UserController::class, 'documents'])->name('users.documents');
    Route::delete('/users/documents/{document}', [UserController::class, 'destroy_document'])->name('users.destroy_document');
    Route::get('/users/statistics/show', [UserController::class, 'statistics'])->name('users.statistics');

    Route::apiResource('/settings', SettingController::class)->names('settings');
    Route::apiResource('/setting-groups', SettingGroupController::class)->names('setting-groups');
    Route::apiResource('/roles', RoleController::class)->names('roles');
    Route::apiResource('/ai-summaries', AiSummaryController::class)->names('ai-summaries');
    Route::apiResource('/chatbot-questions', ChatbotQuestionController::class)->names('chatbot-questions');
    Route::apiResource('/chats', ChatController::class)->names('chats');
    Route::apiResource('/favorites', FavoriteController::class)->names('favorites');
    Route::apiResource('/downloads', DownloadController::class)->names('downloads');
    Route::apiResource('/ai-voices', AiVoiceController::class)->names('ai-voices');
    Route::apiResource('/categories', CategoryController::class)->names('categories');

    Route::get('/documents/favorites/user_favorites', [DocumentController::class, 'user_favorites'])->name('documents.user_favorites');
    Route::get('/documents/liked/user_liked', [DocumentController::class, 'user_liked'])->name('documents.user_liked');
    Route::apiResource('/documents', DocumentController::class)->names('documents');
    Route::post('/documents/{document}/favorite', [DocumentController::class, 'favorite'])->name('documents.favorite');
    Route::delete('/documents/{document}/unfavorite', [DocumentController::class, 'unfavorite'])->name('documents.unfavorite');
    Route::get('/documents/{document}/is_favorite', [DocumentController::class, 'is_favorite'])->name('documents.is_favorite');
    Route::get('/documents/{document}/comments', [DocumentController::class, 'get_comments'])->name('documents.comments');
    Route::get('/documents/{document}/is_liked', [DocumentController::class, 'is_liked'])->name('documents.is_liked');
    Route::post('/documents/{document}/like', [DocumentController::class, 'like'])->name('documents.like');
    Route::delete('/documents/{document}/unlike', [DocumentController::class, 'unlike'])->name('documents.unlike');

    Route::apiResource('/files', FileController::class)->names('files');
    Route::apiResource('/views', ViewController::class)->names('views');

    Route::apiResource('/comments', CommentController::class)->names('comments');
    Route::get('/comments/{document}/get_like_ids', [CommentController::class, 'get_like_ids'])->name('comments.get_like_ids');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::delete('/comments/{comment}/unlike', [CommentController::class, 'unlike'])->name('comments.unlike');

    Route::apiResource('/ratings', RatingController::class)->names('ratings');
});

require __DIR__ . '/auth.php';

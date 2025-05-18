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
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\AiVoiceController;
use App\Http\Controllers\DocumentPurchaseController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\AuthorProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PointPackageController;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/chats')->group(function () {
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
    Route::post('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::post('/documents/{document}/ai-summary', [DocumentController::class, 'ai_summary'])->name('documents.ai_summary');
    Route::post('/documents/{document}/ai-voice', [DocumentController::class, 'ai_voice'])->name('documents.ai_voice');
    Route::get('/documents/{document}/chat', [DocumentController::class, 'chat'])->name('documents.chat');
    Route::get('/documents/{document}/is_purchased', [DocumentController::class, 'is_purchased'])->name('documents.is_purchased');
    Route::post('/documents/{document}/purchase', [DocumentController::class, 'purchase'])->name('documents.purchase');
    Route::get('/documents/top_documents/show', [DocumentController::class, 'top_documents'])->name('documents.top_documents');
    Route::get('/documents/new_documents/show', [DocumentController::class, 'new_documents'])->name('documents.new_documents');

    Route::apiResource('/files', FileController::class)->names('files');
    Route::apiResource('/views', ViewController::class)->names('views');

    Route::apiResource('/comments', CommentController::class)->names('comments');
    Route::get('/comments/{document}/get_like_ids', [CommentController::class, 'get_like_ids'])->name('comments.get_like_ids');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::delete('/comments/{comment}/unlike', [CommentController::class, 'unlike'])->name('comments.unlike');

    Route::apiResource('/ratings', RatingController::class)->names('ratings');

    Route::get('user/inquiries', [InquiryController::class, 'userInquiries']);
    // Route::post('inquiries', [InquiryController::class, 'store']);
    // Route::get('inquiries/{inquiry}', [InquiryController::class, 'show']);
    Route::apiResource('/inquiries', InquiryController::class)->names('inquiries');

    Route::get('admin/inquiries', [InquiryController::class, 'index']);
    Route::post('inquiries/{inquiry}/respond', [InquiryController::class, 'respond']);

    Route::apiResource('/topics', TopicController::class)->names('topics');
    Route::apiResource('/menus', MenuController::class)->names('menus');

    Route::get('/interaction/statistics', [InteractionController::class, 'get_interaction_statistics'])->name('interaction.statistics');
    Route::get('/interaction/data', [InteractionController::class, 'get_interaction_data'])->name('interaction.data');

    Route::apiResource('publishers', PublisherController::class);
    Route::get('publishers/{publisher}/documents', [PublisherController::class, 'documents']);
    Route::get('publishers/{publisher}/statistics', [PublisherController::class, 'statistics']);

    Route::apiResource('author-profiles', AuthorProfileController::class);
    Route::get('author-profiles/{authorProfile}/documents', [AuthorProfileController::class, 'documents']);
    Route::get('authors', [AuthorProfileController::class, 'getAuthors']);

    Route::apiResource('/transactions', TransactionController::class)->names('transactions');
    Route::apiResource('/document_purchases', DocumentPurchaseController::class)->names('document_purchases');

    Route::apiResource('/point_packages', PointPackageController::class)->names('point_packages');
});

// PayPal routes
Route::prefix('payment')->group(function () {
    Route::post('/create', [PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');

    // Trang hoàn thành và trang lỗi
    Route::view('/completed', 'payments.completed')->name('payment.completed');
    Route::view('/failed', 'payments.failed')->name('payment.failed');
});

require __DIR__ . '/auth.php';

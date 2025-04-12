<?php

use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/test', function () {
    return view('test.index');
});

// Route::post('/test/chat', [ChatBotController::class, 'chatApi'])->name('test.chat');

Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatBotController::class, 'index'])->name('chat.index');
    Route::get('/chat/{uuid}', [ChatBotController::class, 'show'])->name('chat.show');
    Route::post('/chat', [ChatBotController::class, 'store'])->name('chat.store');
    Route::post('/chat/{chat_id}/message', [ChatBotController::class, 'chatApi'])->name('chat.message');
});
require __DIR__ . '/auth.php';

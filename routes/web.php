<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::get('/', [IndexController::class, 'home'])->name('home');
    Route::get('/chat/{chat_id}', [IndexController::class, 'home'])->name('chat.show');

    Route::inertia('/about', 'About')->name('about');

    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('chats', ChatController::class)->except(['show']);
    Route::resource('messages', MessageController::class);
});

Route::middleware('guest')->group(function () {
    Route::inertia('/register', 'Auth/Register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::inertia('/login', 'Auth/Login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

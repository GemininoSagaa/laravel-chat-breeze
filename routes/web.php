<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\GroupController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    
    // Rutas para amistades
    Route::get('/friends', [FriendshipController::class, 'index'])->name('friends.index');
    Route::post('/friends/{user}', [FriendshipController::class, 'sendRequest'])->name('friends.request');
    Route::put('/friends/{friendship}/accept', [FriendshipController::class, 'acceptRequest'])->name('friends.accept');
    Route::delete('/friends/{friendship}', [FriendshipController::class, 'rejectRequest'])->name('friends.reject');
    
    // Rutas para mensajes
    Route::get('/chat/{friend?}', [MessageController::class, 'index'])->name('chat.individual');
    Route::get('/chat/group/{group}', [MessageController::class, 'index'])->name('chat.group');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/typing', [MessageController::class, 'userTyping'])->name('messages.typing');
    
    // Rutas para grupos
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'delete'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Client\ClientController;

Route::get('/', function () {
    return view('welcome');
});

// Routes d'authentification client
Route::prefix('client')->name('client.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/register', [ClientAuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [ClientAuthController::class, 'register']);
        Route::get('/login', [ClientAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [ClientAuthController::class, 'login']);
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [ClientAuthController::class, 'logout'])->name('logout');
        
        // Dashboard client
        Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
        
        // Documents
        Route::get('/documents', [ClientController::class, 'documents'])->name('documents');
        Route::get('/documents/create', [ClientController::class, 'createDocument'])->name('documents.create');
        Route::post('/documents', [ClientController::class, 'storeDocument'])->name('documents.store');
        Route::delete('/documents/{document}', [ClientController::class, 'destroyDocument'])->name('documents.destroy');
        
        // Paiements
        Route::get('/paiements', [ClientController::class, 'paiements'])->name('paiements');
        Route::get('/paiements/create', [ClientController::class, 'createPaiement'])->name('paiements.create');
        Route::post('/paiements', [ClientController::class, 'storePaiement'])->name('paiements.store');
        
        // Notifications
        Route::get('/notifications', [ClientController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/{notification}/read', [ClientController::class, 'markNotificationAsRead'])->name('notifications.markAsRead');
        Route::post('/notifications/read-all', [ClientController::class, 'markAllNotificationsAsRead'])->name('notifications.markAllAsRead');
        
        // Profil
        Route::get('/profile', [ClientController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [ClientController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile/update', [ClientController::class, 'updateProfile'])->name('profile.update');
        Route::get('/profile/change-password', [ClientController::class, 'changePassword'])->name('password.change');
        Route::post('/profile/update-password', [ClientController::class, 'updatePassword'])->name('password.update');
    });
});

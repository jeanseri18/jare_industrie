<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\HistoryController as AdminHistoryController;
use App\Http\Controllers\Admin\BackupController as AdminBackupController;

Route::get('/', function () {
    return view('welcome');
});

   Route::middleware('guest')->group(function () {
        Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });
// Routes d'authentification client
Route::prefix('client')->name('client.')->group(function () {
 

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        // Dashboard client
        Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    });
});

// Routes pour DG
Route::prefix('dg')->name('dg.')->middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dg.dashboard');
    })->name('dashboard');
    
    // Inclure les routes DG
    require __DIR__.'/dg.php';
});

// Routes pour Comptable
Route::prefix('comptable')->name('comptable.')->middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('comptable.dashboard');
    })->name('dashboard');
});

// Routes pour Chef Commercial
Route::prefix('chef_commercial')->name('chef_commercial.')->middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('chef_commercial.dashboard');
    })->name('dashboard');
});

// Routes pour Admin Technique
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des utilisateurs
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/password', [AdminUserController::class, 'updatePassword'])->name('users.password.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    
    // Historique des actions
    Route::get('/history', [AdminHistoryController::class, 'index'])->name('history.index');
    Route::post('/history/clear', [AdminHistoryController::class, 'clear'])->name('history.clear');
    
    // Sauvegarde de la base de données
    Route::get('/backup', [AdminBackupController::class, 'index'])->name('backup.index');
    Route::post('/backup', [AdminBackupController::class, 'create'])->name('backup.create');
    Route::get('/backup/{backup}/download', [AdminBackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/{backup}', [AdminBackupController::class, 'destroy'])->name('backup.destroy');
});

// Routes pour Opérateur de saisie
Route::prefix('operateur')->name('operateur.')->middleware(['auth', 'role:operateur'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Operateur\OperateurController::class, 'dashboard'])->name('dashboard');
    Route::get('/souscriptions/create', [App\Http\Controllers\Operateur\OperateurController::class, 'create'])->name('souscriptions.create');
    Route::post('/souscriptions', [App\Http\Controllers\Operateur\OperateurController::class, 'store'])->name('souscriptions.store');
});

// Route de déconnexion générale
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

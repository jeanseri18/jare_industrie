<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\OnboardingController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\HistoryController as AdminHistoryController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/contact', [\App\Http\Controllers\Public\ContactController::class, 'store'])->name('contact.store');

Route::middleware('signed')->prefix('p')->name('public.')->group(function () {
    Route::get('/paiements/{paiement}/recu', [\App\Http\Controllers\Public\DocumentController::class, 'recu'])->name('paiements.recu');
    Route::get('/paiements/{paiement}/preuve', [\App\Http\Controllers\Public\DocumentController::class, 'preuvePaiement'])->name('paiements.preuve');
    Route::get('/souscriptions/{souscription}/fiche', [\App\Http\Controllers\Public\DocumentController::class, 'ficheSouscription'])->name('souscriptions.fiche');
    Route::get('/souscriptions/{souscription}/contrat-reservation', [\App\Http\Controllers\Public\DocumentController::class, 'contratReservation'])->name('souscriptions.contrat-reservation');
    Route::get('/souscriptions/{souscription}/lettre-definitive', [\App\Http\Controllers\Public\DocumentController::class, 'lettreDefinitive'])->name('souscriptions.lettre-definitive');
    Route::get('/souscriptions/{souscription}/etat-versements', [\App\Http\Controllers\Public\DocumentController::class, 'etatVersements'])->name('souscriptions.etat-versements');
    Route::get('/souscriptions/{souscription}/attestation-reservation', [\App\Http\Controllers\Public\DocumentController::class, 'attestationReservation'])->name('souscriptions.attestation-reservation');
});

   Route::middleware('guest')->group(function () {
        Route::get('/register', [OnboardingController::class, 'showAccount'])->name('register');
        Route::post('/register', [OnboardingController::class, 'storeAccount'])->name('register.account');
        Route::get('/register/entreprise', [OnboardingController::class, 'showCompany'])->name('register.entreprise');
        Route::post('/register/entreprise', [OnboardingController::class, 'storeCompany'])->name('register.entreprise.store');
        Route::get('/register/identite', [OnboardingController::class, 'showBranding'])->name('register.identite');
        Route::post('/register/identite', [OnboardingController::class, 'complete'])->name('register.complete');

        Route::get('/register/equipe', [AuthController::class, 'showRegistrationForm'])->name('register.equipe');
        Route::post('/register/equipe', [AuthController::class, 'register'])->name('register.equipe.store');

        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });
// Routes d'authentification client
Route::prefix('client')->name('client.')->middleware(['auth', 'org'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        // Dashboard client
        Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
        
        // Historique des paiements
        Route::get('/historique', [ClientController::class, 'historique'])->name('historique');
        
        // Mes souscriptions
        Route::get('/mes-souscriptions', [ClientController::class, 'souscriptions'])->name('souscriptions');
        
        // Mes documents
        Route::get('/mes-documents', [ClientController::class, 'documents'])->name('documents');
        
        // Notifications
        Route::get('/notifications', [ClientController::class, 'notifications'])->name('notifications');
        
        // Profil
        Route::get('/profile', [ClientController::class, 'profile'])->name('profile');
        Route::put('/profile', [ClientController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [ClientController::class, 'updatePassword'])->name('password.update');
});

// Routes partagées pour tout le staff
Route::middleware(['auth'])->group(function () {
    Route::get('/mon-profil', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/mon-profil/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// Routes pour DG
Route::prefix('dg')->name('dg.')->middleware(['auth', 'org', 'role:dg,admin_technique'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DG\DashboardController::class, 'index'])->name('dashboard');
    
    // Inclure les routes DG
    require __DIR__.'/dg.php';
});

// Routes pour Comptable
Route::middleware(['auth', 'org', 'role:comptable'])->group(function () {
    // Inclure les routes Comptable
    require __DIR__.'/comptable.php';
});

// Routes pour Chef Commercial
Route::prefix('chef_commercial')->name('chef_commercial.')->middleware(['auth', 'org', 'role:chef_commercial'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\ChefCommercial\ChefCommercialController::class, 'dashboard'])
        ->name('dashboard');

    // Liste des souscriptions corrigées
    Route::get('/souscriptions/corrigees', [\App\Http\Controllers\ChefCommercial\ChefCommercialController::class, 'corrigees'])
        ->name('souscriptions.corrigees');

    Route::get('/souscriptions/corrige', [\App\Http\Controllers\ChefCommercial\ChefCommercialController::class, 'corrige'])
        ->name('souscriptions.corrige');

    // Nouvelle souscription (contrôleur)
    Route::get('/souscriptions/create', [\App\Http\Controllers\ChefCommercial\ChefCommercialController::class, 'create'])
        ->name('souscriptions.create');

    // Enregistrer souscription (contrôleur)
    Route::post('/souscriptions', [\App\Http\Controllers\ChefCommercial\ChefCommercialController::class, 'store'])
        ->name('souscriptions.store');
    Route::get('/souscriptions/{souscription}/fiche-souscription', [\App\Http\Controllers\SouscriptionController::class, 'downloadFicheSouscription'])
        ->name('souscriptions.fiche-souscription');
    Route::post('/souscriptions/{souscription}/fiche-souscription/envoyer', [\App\Http\Controllers\SouscriptionController::class, 'sendFicheSouscription'])
        ->name('souscriptions.fiche-souscription.send');

    // Modifier une souscription (contrôleur)
    Route::get('/souscriptions/{souscription}/edit', [\App\Http\Controllers\ChefCommercial\ChefCommercialController::class, 'edit'])
        ->name('souscriptions.edit');
    Route::put('/souscriptions/{souscription}', [\App\Http\Controllers\ChefCommercial\ChefCommercialController::class, 'update'])
        ->name('souscriptions.update');
});

// Routes pour Admin Technique
Route::prefix('admin')->name('admin.')->middleware(['auth', 'org', 'role:admin_technique'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des utilisateurs
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/password', [AdminUserController::class, 'updatePassword'])->name('users.password.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Gestion des clients
    Route::get('/clients', [AdminClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{user}/password', [AdminClientController::class, 'editPassword'])->name('clients.password.edit');
    Route::put('/clients/{user}/password', [AdminClientController::class, 'updatePassword'])->name('clients.password.update');
    
    // Historique des actions
    Route::get('/history', [AdminHistoryController::class, 'index'])->name('history.index');
    Route::post('/history/clear', [AdminHistoryController::class, 'clear'])->name('history.clear');
});

// Routes pour Opérateur de saisie
Route::prefix('operateur')->name('operateur.')->middleware(['auth', 'org', 'role:operateur'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Operateur\OperateurController::class, 'dashboard'])->name('dashboard');
    Route::get('/souscriptions/create', [App\Http\Controllers\Operateur\OperateurController::class, 'create'])->name('souscriptions.create');
    Route::post('/souscriptions', [App\Http\Controllers\Operateur\OperateurController::class, 'store'])->name('souscriptions.store');
    Route::get('/souscriptions/{souscription}/fiche-souscription', [\App\Http\Controllers\SouscriptionController::class, 'downloadFicheSouscription'])
        ->name('souscriptions.fiche-souscription');
    Route::post('/souscriptions/{souscription}/fiche-souscription/envoyer', [\App\Http\Controllers\SouscriptionController::class, 'sendFicheSouscription'])
        ->name('souscriptions.fiche-souscription.send');
});

// Route de déconnexion générale
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::prefix('platform')->name('platform.')->group(function () {
    require __DIR__.'/platform.php';
});

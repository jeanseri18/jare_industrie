<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DG\ClientController;
use App\Http\Controllers\DG\ProjetController;
use App\Http\Controllers\DG\MutuelleController;
use App\Http\Controllers\DG\BienImmobilierController;
use App\Http\Controllers\SouscriptionController;
use App\Http\Controllers\DG\EquipeController;
use App\Models\Souscription;

// Routes pour les clients
Route::resource('clients', ClientController::class);

// Routes pour les projets
Route::resource('projets', ProjetController::class);

// Routes pour les mutuelles
Route::resource('mutuelles', MutuelleController::class);

// Routes pour les souscriptions
Route::resource('souscriptions', SouscriptionController::class);
// Enregistrement de l'attribution d'un logement
Route::post('souscriptions/{souscription}/attribuer', [SouscriptionController::class, 'attribuer'])
    ->name('souscriptions.attribuer');
// Affichage du formulaire de validation finale
Route::get('souscriptions/{souscription}/confirmation', [SouscriptionController::class, 'confirmationForm'])
    ->name('souscriptions.confirmation');
// Enregistrement de la validation finale
Route::post('souscriptions/{souscription}/validation-finale', [SouscriptionController::class, 'validerDefinitive'])
    ->name('souscriptions.validation-finale');

// Routes pour les biens immobiliers des projets
Route::resource('projets.biens', BienImmobilierController::class)->shallow();

// Attribution de logement (liste des souscriptions à attribuer)
Route::get('attribution', [\App\Http\Controllers\DG\GestionSouscriptionController::class, 'attributionIndex'])
    ->name('attribution.index');

// Confirmation de dossier (liste des souscriptions soldées)
Route::get('confirmation', [\App\Http\Controllers\DG\GestionSouscriptionController::class, 'confirmationIndex'])
    ->name('confirmation.index');

// Suivi des équipes (activation de compte)
Route::get('equipes', [EquipeController::class, 'index'])->name('equipes.index');
Route::get('equipes/create', [EquipeController::class, 'create'])->name('equipes.create');
Route::post('equipes', [EquipeController::class, 'store'])->name('equipes.store');
// Edition des informations d'un utilisateur d'équipe
Route::get('equipes/{user}/edit', [EquipeController::class, 'edit'])->name('equipes.edit');
Route::put('equipes/{user}', [EquipeController::class, 'update'])->name('equipes.update');
// Actions d'activation/refus
Route::post('equipes/{user}/activer', [EquipeController::class, 'activer'])->name('equipes.activer');
Route::post('equipes/{user}/refuser', [EquipeController::class, 'refuser'])->name('equipes.refuser');

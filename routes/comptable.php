<?php

use App\Http\Controllers\Comptable\ComptableController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:comptable'])->prefix('comptable')->name('comptable.')->group(function () {
    
    // Tableau de bord
    Route::get('/dashboard', [ComptableController::class, 'dashboard'])->name('dashboard');
    
    // Frais de dossier
    Route::get('/frais-dossier', [ComptableController::class, 'fraisDossier'])->name('frais-dossier');
    // Paiement Frais de dossier
    Route::post('/frais-dossier/{fraisDossier}/payer', [ComptableController::class, 'payerFraisDossier'])->name('frais-dossier.payer');
    
    // Apports initiaux
    Route::get('/apports-initiaux', [ComptableController::class, 'apportsInitiaux'])->name('apports-initiaux');
    // Paiement Apports initiaux
    Route::post('/apports-initiaux/{apportInitial}/payer', [ComptableController::class, 'payerApportInitial'])->name('apports-initiaux.payer');
    
    // Suivi des paiements projet
    Route::get('/suivi-paiements-projet', [ComptableController::class, 'suiviPaiementsProjet'])->name('suivi-paiements-projet');
    
    // Projets soldés
    Route::get('/projets-soldes', [ComptableController::class, 'projetsSoldes'])->name('projets-soldes');

    // Édition de reçus
    Route::get('/edition-recus', [ComptableController::class, 'editionRecus'])->name('edition-recus');

    // Dossiers annulés
    Route::get('/dossiers-annules', [ComptableController::class, 'dossiersAnnules'])->name('dossiers-annules');
    // Remboursement d'un dossier annulé
    Route::post('/dossiers-annules/{souscription}/rembourser', [ComptableController::class, 'rembourserDossier'])->name('dossiers-annules.rembourser');
    
    // Gestion des Clients
    Route::get('/clients', [ComptableController::class, 'clientsIndex'])->name('clients.index');
    Route::get('/clients/{client}', [ComptableController::class, 'clientsShow'])->name('clients.show');
    
    // Actions sur les paiements
    Route::post('/paiements/{paiement}/valider', [ComptableController::class, 'validerPaiement'])->name('paiements.valider');
    Route::post('/paiements/{paiement}/annuler', [ComptableController::class, 'annulerPaiement'])->name('paiements.annuler');
    Route::put('/paiements/{paiement}/statut', [ComptableController::class, 'updateStatut'])->name('paiements.update-statut');
    Route::get('/paiements/{paiement}/recu', [ComptableController::class, 'recu'])->name('paiements.recu');
    
    // Nouvelles routes pour la gestion des paiements par souscription
    Route::get('/souscriptions/{souscription}/etat-versements', [ComptableController::class, 'etatVersements'])->name('souscriptions.etat-versements');
    Route::post('/souscriptions/{souscription}/paiements', [ComptableController::class, 'createPaiement'])->name('paiements.create');
    Route::get('/souscriptions/{souscription}/paiements', [ComptableController::class, 'voirPaiementsSouscription'])->name('paiements.souscription');
    
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DG\ClientController;
use App\Http\Controllers\DG\ProjetController;
use App\Http\Controllers\DG\MutuelleController;
use App\Http\Controllers\DG\BienImmobilierController;
use App\Http\Controllers\DG\ProjetLotController;
use App\Http\Controllers\SouscriptionController;
use App\Http\Controllers\DG\EquipeController;
use App\Http\Controllers\DG\AdminTechniqueController;
use App\Models\Souscription;

Route::get('clients/export/pdf', [ClientController::class, 'exportPdf'])->name('clients.export.pdf');
Route::get('clients/export/excel', [ClientController::class, 'exportExcel'])->name('clients.export.excel');
Route::get('clients/{client}/password', [ClientController::class, 'editPassword'])->name('clients.password.edit');
Route::put('clients/{client}/password', [ClientController::class, 'updatePassword'])->name('clients.password.update');
Route::post('clients/{client}/account', [ClientController::class, 'createAccount'])->name('clients.account.create');

// Routes pour les clients
Route::resource('clients', ClientController::class);

// Routes pour les projets
Route::get('projets/{projet}/apres-creation', [ProjetController::class, 'apresCreation'])->name('projets.apres_creation');
Route::resource('projets', ProjetController::class);

// Routes pour les mutuelles
Route::get('mutuelles/projets/{projet}/biens', [MutuelleController::class, 'getBiens'])->name('mutuelles.getBiens');
Route::resource('mutuelles', MutuelleController::class);

// Routes pour les souscriptions
Route::resource('souscriptions', SouscriptionController::class)->names([
    'index' => 'souscriptions.index',
    'create' => 'souscriptions.create',
    'store' => 'souscriptions.store',
    'show' => 'souscriptions.show',
    'edit' => 'souscriptions.edit',
    'update' => 'souscriptions.update',
    'destroy' => 'souscriptions.destroy',
]);
// Annulation d'une souscription
Route::post('souscriptions/{souscription}/annuler', [SouscriptionController::class, 'annuler'])
    ->name('souscriptions.annuler');
// Réactivation d'une souscription
Route::post('souscriptions/{souscription}/reactiver', [SouscriptionController::class, 'reactiver'])
    ->name('souscriptions.reactiver');
// Enregistrement de l'attribution d'un logement
Route::post('souscriptions/{souscription}/attribuer', [SouscriptionController::class, 'attribuer'])
    ->name('souscriptions.attribuer');
// Téléchargement de l'attestation
Route::get('souscriptions/{souscription}/attestation', [SouscriptionController::class, 'downloadAttestation'])
    ->name('souscriptions.attestation');
Route::get('souscriptions/{souscription}/fiche-souscription', [SouscriptionController::class, 'downloadFicheSouscription'])
    ->name('souscriptions.fiche-souscription');
Route::post('souscriptions/{souscription}/fiche-souscription/envoyer', [SouscriptionController::class, 'sendFicheSouscription'])
    ->name('souscriptions.fiche-souscription.send');
// Affichage du formulaire de validation finale
Route::get('souscriptions/{souscription}/confirmation', [SouscriptionController::class, 'confirmationForm'])
    ->name('souscriptions.confirmation');
// Enregistrement de la validation finale
Route::post('souscriptions/{souscription}/validation-finale', [SouscriptionController::class, 'validerDefinitive'])
    ->name('souscriptions.validation-finale');
Route::get('souscriptions/{souscription}/lettre-definitive', [SouscriptionController::class, 'downloadLettreDefinitive'])
    ->name('souscriptions.lettre-definitive');
Route::get('souscriptions/{souscription}/paiements', [SouscriptionController::class, 'voirPaiements'])
    ->name('souscriptions.paiements');
Route::get('souscriptions/{souscription}/etat-versements', [\App\Http\Controllers\Comptable\ComptableController::class, 'etatVersements'])
    ->name('souscriptions.etat-versements');
Route::get('paiements/{paiement}/recu', [\App\Http\Controllers\Comptable\ComptableController::class, 'recu'])
    ->name('paiements.recu');

// Routes pour les biens immobiliers des projets
Route::get('projets/{projet}/biens/{bien}/apres-creation', [BienImmobilierController::class, 'apresCreation'])->name('projets.biens.apres_creation');
Route::resource('projets.biens', BienImmobilierController::class)->shallow();

// Inventaire îlots & lots (par projet)
Route::get('projets/{projet}/lots', [ProjetLotController::class, 'index'])->name('projets.lots.index');
Route::get('projets/{projet}/lots/ilot/{ilot}/liste', [ProjetLotController::class, 'showIlotLots'])->name('projets.lots.ilot.lots');
Route::post('projets/{projet}/lots/ilot/{ilot}/lot', [ProjetLotController::class, 'storeLotManuel'])->name('projets.lots.ilot.lot.store');
Route::post('projets/{projet}/lots/ilot/{ilot}/superficie-masse', [ProjetLotController::class, 'updateSuperficieMasse'])->name('projets.lots.ilot.superficie_masse');
Route::post('projets/{projet}/lots/ilot/{ilot}/bien-masse', [ProjetLotController::class, 'updateBienImmobilierMasse'])->name('projets.lots.ilot.bien_masse');
Route::patch('projets/{projet}/lots/{projet_lot}/superficie', [ProjetLotController::class, 'updateLotSuperficie'])->name('projets.lots.lot.superficie');
Route::patch('projets/{projet}/lots/{projet_lot}/bien', [ProjetLotController::class, 'updateLotBienImmobilier'])->name('projets.lots.lot.bien');
Route::post('projets/{projet}/lots', [ProjetLotController::class, 'store'])->name('projets.lots.store');
Route::patch('projets/{projet}/lots/ilot/{ilot}', [ProjetLotController::class, 'updateIlot'])->name('projets.lots.ilot.update');
Route::post('projets/{projet}/lots/ilot/{ilot}/duplicate', [ProjetLotController::class, 'duplicateIlot'])->name('projets.lots.ilot.duplicate');
Route::delete('projets/{projet}/lots/ilot/{ilot}', [ProjetLotController::class, 'destroyIlot'])->name('projets.lots.ilot.destroy');
Route::delete('projets/{projet}/lots/{projet_lot}', [ProjetLotController::class, 'destroy'])->name('projets.lots.destroy');

// Attribution de logement (liste des souscriptions à attribuer)
Route::get('attribution', [\App\Http\Controllers\DG\GestionSouscriptionController::class, 'attributionIndex'])
    ->name('attribution.index');
// Attribution de logement (fiche d'attribution d'une souscription)
Route::get('attribution/{souscription}', [\App\Http\Controllers\DG\GestionSouscriptionController::class, 'attributionShow'])
    ->name('attribution.show');

// Confirmation de dossier (liste des souscriptions soldées)
Route::get('confirmation', [\App\Http\Controllers\DG\GestionSouscriptionController::class, 'confirmationIndex'])
    ->name('confirmation.index');

Route::get('suivi-paiements-projet', [\App\Http\Controllers\DG\GestionSouscriptionController::class, 'suiviPaiementsProjet'])
    ->name('suivi-paiements-projet');

// Paiement direct (DG) depuis le suivi projet
Route::post('souscriptions/{souscription}/paiements', [\App\Http\Controllers\DG\GestionSouscriptionController::class, 'createPaiement'])
    ->name('paiements.create');

// Paiements (DG) - mêmes écrans/actions que comptabilité
Route::get('frais-dossier', [\App\Http\Controllers\DG\GestionSouscriptionController::class, 'fraisDossier'])
    ->name('frais-dossier');
Route::post('frais-dossier/{fraisDossier}/payer', [\App\Http\Controllers\DG\GestionSouscriptionController::class, 'payerFraisDossier'])
    ->name('frais-dossier.payer');

Route::get('apports-initiaux', [\App\Http\Controllers\DG\GestionSouscriptionController::class, 'apportsInitiaux'])
    ->name('apports-initiaux');
Route::post('apports-initiaux/{apportInitial}/payer', [\App\Http\Controllers\DG\GestionSouscriptionController::class, 'payerApportInitial'])
    ->name('apports-initiaux.payer');

Route::get('dossiers-annules', [\App\Http\Controllers\DG\GestionSouscriptionController::class, 'dossiersAnnules'])
    ->name('dossiers-annules');
Route::post('dossiers-annules/{souscription}/rembourser', [\App\Http\Controllers\DG\GestionSouscriptionController::class, 'rembourserDossier'])
    ->name('dossiers-annules.rembourser');

// Suivi des équipes (activation de compte)
Route::get('equipes', [EquipeController::class, 'index'])->name('equipes.index');
Route::get('equipes/create', [EquipeController::class, 'create'])->name('equipes.create');
Route::post('equipes', [EquipeController::class, 'store'])->name('equipes.store');
// Edition des informations d'un utilisateur d'équipe
Route::get('equipes/{user}/edit', [EquipeController::class, 'edit'])->name('equipes.edit');
Route::put('equipes/{user}', [EquipeController::class, 'update'])->name('equipes.update');
// Historique des actions d'un utilisateur
Route::get('equipes/{user}/logs', [EquipeController::class, 'logs'])->name('equipes.logs');
// Actions d'activation/refus
Route::post('equipes/{user}/activer', [EquipeController::class, 'activer'])->name('equipes.activer');
Route::post('equipes/{user}/refuser', [EquipeController::class, 'refuser'])->name('equipes.refuser');

Route::get('admin-technique', [AdminTechniqueController::class, 'dashboard'])->name('admin_technique.dashboard');

Route::get('parametres/identite', [\App\Http\Controllers\DG\OrganizationBrandingController::class, 'edit'])->name('parametres.identite');
Route::put('parametres/identite', [\App\Http\Controllers\DG\OrganizationBrandingController::class, 'update'])->name('parametres.identite.update');
Route::get('parametres/identite/apercu-pdf', [\App\Http\Controllers\DG\OrganizationBrandingController::class, 'preview'])->name('parametres.identite.preview');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DG\ClientController;
use App\Http\Controllers\DG\ProjetController;
use App\Http\Controllers\DG\MutuelleController;
use App\Http\Controllers\SouscriptionController;

// Routes pour les clients
Route::resource('clients', ClientController::class);

// Routes pour les projets
Route::resource('projets', ProjetController::class);

// Routes pour les mutuelles
Route::resource('mutuelles', MutuelleController::class);

// Routes pour les souscriptions

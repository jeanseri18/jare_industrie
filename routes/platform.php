<?php

use App\Http\Controllers\Platform\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'super_admin'])->group(function () {
    Route::get('/', fn () => redirect()->route('platform.organizations.index'))->name('dashboard');
    Route::resource('organizations', OrganizationController::class)->except(['show', 'destroy']);
});

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Client;
use App\Models\Projet;
use App\Models\Souscription;
use App\Models\Paiement;
use App\Observers\UserObserver;
use App\Observers\ClientObserver;
use App\Observers\ProjetObserver;
use App\Observers\SouscriptionObserver;
use App\Observers\PaiementObserver;
use App\Services\ActivityLogger;

class ActivityLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ActivityLogger::class, function ($app) {
            return new ActivityLogger();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Enregistrer les observateurs
        User::observe(UserObserver::class);
        Client::observe(ClientObserver::class);
        Projet::observe(ProjetObserver::class);
        Souscription::observe(SouscriptionObserver::class);
        Paiement::observe(PaiementObserver::class);

        // Enregistrer les événements d'authentification
        $this->app['events']->listen('Illuminate\Auth\Events\Login', function ($event) {
            // Mettre à jour la date de dernière connexion
            $event->user->update(['last_login_at' => now()]);
            ActivityLogger::logLogin($event->user->email, true);
        });

        $this->app['events']->listen('Illuminate\Auth\Events\Logout', function ($event) {
            ActivityLogger::logLogout();
        });
    }
}
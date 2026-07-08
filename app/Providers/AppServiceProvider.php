<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        view()->composer('layouts.app', \App\View\Composers\AppLayoutComposer::class);
        view()->composer('layouts.client', \App\View\Composers\AppLayoutComposer::class);
        view()->composer('layouts.dg', \App\View\Composers\AppLayoutComposer::class);
        view()->composer('layouts.comptable', \App\View\Composers\AppLayoutComposer::class);
        view()->composer('layouts.admin', \App\View\Composers\AppLayoutComposer::class);
        view()->composer('layouts.chef_commercial', \App\View\Composers\AppLayoutComposer::class);
        view()->composer('layouts.operateur', \App\View\Composers\AppLayoutComposer::class);
        view()->composer(['layouts.guest', 'auth.login', 'auth.register.*', 'welcome'], \App\View\Composers\AppLayoutComposer::class);
    }
}

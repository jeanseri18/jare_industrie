<?php

use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'org' => \App\Http\Middleware\EnsureOrganizationAccess::class,
            'super_admin' => \App\Http\Middleware\EnsureSuperAdmin::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetCurrentOrganization::class,
        ]);

        $middleware->redirectUsersTo(function (Request $request) {
            $user = $request->user();

            return match ($user?->role) {
                User::ROLE_SUPER_ADMIN => route('platform.organizations.index'),
                User::ROLE_DG => route('dg.dashboard'),
                User::ROLE_COMPTABLE => route('comptable.dashboard'),
                User::ROLE_OPERATEUR => route('operateur.dashboard'),
                User::ROLE_CHEF_COMMERCIAL => route('chef_commercial.dashboard'),
                User::ROLE_ADMIN_TECHNIQUE => route('admin.dashboard'),
                User::ROLE_CLIENT => route('client.dashboard'),
                default => route('login'),
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

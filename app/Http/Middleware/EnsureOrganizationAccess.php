<?php

namespace App\Http\Middleware;

use App\Support\CurrentOrganization;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $org = CurrentOrganization::get();

        if (! $org) {
            abort(403, 'Organisation non définie.');
        }

        if ((int) $user->organization_id !== (int) $org->id) {
            abort(403, 'Accès refusé à cette organisation.');
        }

        return $next($request);
    }
}

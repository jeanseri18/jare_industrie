<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use App\Support\CurrentOrganization;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentOrganization
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('platform/*')) {
            CurrentOrganization::set(null);

            return $next($request);
        }

        $organization = $this->resolveOrganization($request);

        if (! $organization && ! $request->is('login', 'register', 'register/*', 'logout', '/', 'up')) {
            if ($request->expectsJson()) {
                abort(404, 'Organisation introuvable.');
            }
        }

        if ($organization) {
            CurrentOrganization::set($organization);
            session(['organization_id' => $organization->id]);
        } elseif (session('organization_id')) {
            $org = Organization::find(session('organization_id'));
            if ($org?->is_active) {
                CurrentOrganization::set($org);
            }
        }

        return $next($request);
    }

    private function resolveOrganization(Request $request): ?Organization
    {
        $host = $request->getHost();
        $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?: $host;

        if ($host !== $baseDomain && str_ends_with($host, '.'.$baseDomain)) {
            $subdomain = str_replace('.'.$baseDomain, '', $host);
            if ($subdomain && $subdomain !== 'www') {
                return Organization::findBySubdomain($subdomain);
            }
        }

        if ($request->filled('org')) {
            return Organization::findBySlug($request->query('org'));
        }

        if ($request->session()->has('organization_id')) {
            return Organization::find($request->session()->get('organization_id'));
        }

        return Organization::findBySubdomain('jare')
            ?? Organization::where('is_active', true)->first();
    }
}

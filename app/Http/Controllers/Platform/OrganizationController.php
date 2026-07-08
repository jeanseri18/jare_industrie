<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationBranding;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::withoutGlobalScopes()
            ->with('branding')
            ->withCount('users', 'projets', 'clients')
            ->latest()
            ->paginate(config('pagination.per_page'))->withQueryString();

        return view('platform.organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('platform.organizations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:100|unique:organizations,slug',
            'subdomain' => 'required|string|max:100|unique:organizations,subdomain|alpha_dash',
            'plan' => 'required|in:starter,pro,enterprise',
            'dg_name' => 'required|string|max:255',
            'dg_email' => 'required|email|max:255',
            'dg_password' => 'required|string|min:8|confirmed',
        ]);

        $org = Organization::withoutGlobalScopes()->create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['slug']),
            'subdomain' => Str::lower($validated['subdomain']),
            'plan' => $validated['plan'],
            'is_active' => true,
            'settings' => [],
        ]);

        OrganizationBranding::create([
            'organization_id' => $org->id,
            'legal_name' => $validated['name'],
            'primary_color' => '#003d82',
            'secondary_color' => '#1e3a8a',
            'accent_color' => '#4CAF50',
        ]);

        User::create([
            'organization_id' => $org->id,
            'name' => $validated['dg_name'],
            'email' => $validated['dg_email'],
            'password' => Hash::make($validated['dg_password']),
            'role' => User::ROLE_DG,
            'requires_dg_validation' => false,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('platform.organizations.index')
            ->with('success', "Organisation « {$org->name} » créée avec succès.");
    }

    public function edit(Organization $organization)
    {
        $organization->load('branding');

        return view('platform.organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:100', Rule::unique('organizations')->ignore($organization->id)],
            'subdomain' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('organizations')->ignore($organization->id)],
            'plan' => 'required|in:starter,pro,enterprise',
            'is_active' => 'boolean',
        ]);

        $organization->update([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('platform.organizations.index')
            ->with('success', 'Organisation mise à jour.');
    }
}

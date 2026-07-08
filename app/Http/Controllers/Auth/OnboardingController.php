<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationBranding;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OnboardingController extends Controller
{
    private const SESSION_ACCOUNT = 'onboarding.account';

    private const SESSION_COMPANY = 'onboarding.company';

    public function showAccount()
    {
        return view('auth.register.account');
    }

    public function storeAccount(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $request->session()->put(self::SESSION_ACCOUNT, $validated);

        return redirect()->route('register.entreprise');
    }

    public function showCompany(Request $request)
    {
        if (! $request->session()->has(self::SESSION_ACCOUNT)) {
            return redirect()->route('register');
        }

        return view('auth.register.entreprise', [
            'company' => $request->session()->get(self::SESSION_COMPANY, []),
        ]);
    }

    public function storeCompany(Request $request)
    {
        if (! $request->session()->has(self::SESSION_ACCOUNT)) {
            return redirect()->route('register');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:100|alpha_dash|unique:organizations,slug',
            'subdomain' => 'required|string|max:100|alpha_dash|unique:organizations,subdomain',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:50',
        ]);

        $request->session()->put(self::SESSION_COMPANY, $validated);

        return redirect()->route('register.identite');
    }

    public function showBranding(Request $request)
    {
        if (! $request->session()->has(self::SESSION_ACCOUNT) || ! $request->session()->has(self::SESSION_COMPANY)) {
            return redirect()->route('register');
        }

        $company = $request->session()->get(self::SESSION_COMPANY);
        $account = $request->session()->get(self::SESSION_ACCOUNT);

        return view('auth.register.identite', [
            'defaults' => [
                'legal_name' => $company['name'] ?? '',
                'tagline' => 'PROMOTEUR IMMOBILIER AGRÉÉ',
                'primary_color' => '#ff7200',
                'secondary_color' => '#1e3a8a',
                'accent_color' => '#4CAF50',
                'address' => $company['address'] ?? '',
                'city' => $company['city'] ?? '',
                'country' => $company['country'] ?? '',
                'phone' => $company['phone'] ?? '',
                'email' => $account['email'] ?? '',
                'director_name' => trim(($account['prenom'] ?? '').' '.($account['nom'] ?? '')),
                'director_title' => 'Directeur Général',
            ],
        ]);
    }

    public function complete(Request $request)
    {
        if (! $request->session()->has(self::SESSION_ACCOUNT) || ! $request->session()->has(self::SESSION_COMPANY)) {
            return redirect()->route('register');
        }

        $account = $request->session()->get(self::SESSION_ACCOUNT);
        $company = $request->session()->get(self::SESSION_COMPANY);

        $validated = $request->validate([
            'legal_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'accent_color' => 'required|string|max:7',
            'director_name' => 'nullable|string|max:255',
            'director_title' => 'nullable|string|max:255',
            'rccm' => 'nullable|string|max:100',
            'cc' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'website' => 'nullable|string|max:255',
            'footer_text' => 'nullable|string|max:2000',
            'logo' => 'nullable|image|max:2048',
            'watermark' => 'nullable|image|max:4096',
            'signature' => 'nullable|image|max:2048',
        ]);

        $user = DB::transaction(function () use ($account, $company, $validated, $request) {
            $org = Organization::withoutGlobalScopes()->create([
                'name' => $company['name'],
                'slug' => Str::slug($company['slug']),
                'subdomain' => Str::lower($company['subdomain']),
                'is_active' => true,
                'plan' => 'pro',
                'settings' => ['onboarding_completed_at' => now()->toIso8601String()],
            ]);

            $branding = OrganizationBranding::create([
                'organization_id' => $org->id,
                'legal_name' => $validated['legal_name'],
                'tagline' => $validated['tagline'] ?? null,
                'primary_color' => $validated['primary_color'],
                'secondary_color' => $validated['secondary_color'],
                'accent_color' => $validated['accent_color'],
                'director_name' => $validated['director_name'] ?? trim($account['prenom'].' '.$account['nom']),
                'director_title' => $validated['director_title'] ?? 'Directeur Général',
                'rccm' => $validated['rccm'] ?? null,
                'cc' => $validated['cc'] ?? null,
                'address' => $company['address'],
                'city' => $company['city'],
                'country' => $company['country'],
                'phone' => $validated['phone'] ?? $company['phone'],
                'whatsapp' => $validated['whatsapp'] ?? null,
                'email' => $validated['email'] ?? $account['email'],
                'website' => $validated['website'] ?? null,
                'footer_text' => $validated['footer_text'] ?? null,
            ]);

            $this->storeBrandingFiles($branding, $request, $org->id);

            $user = User::create([
                'organization_id' => $org->id,
                'name' => $account['nom'].' '.$account['prenom'],
                'email' => $account['email'],
                'password' => Hash::make($account['password']),
                'role' => User::ROLE_DG,
                'telephone' => $account['telephone'],
                'adresse' => $company['address'],
                'requires_dg_validation' => false,
                'email_verified_at' => now(),
            ]);

            return $user;
        });

        $request->session()->forget([self::SESSION_ACCOUNT, self::SESSION_COMPANY]);
        $request->session()->put('organization_id', $user->organization_id);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dg.dashboard')
            ->with('success', 'Votre entreprise est prête. Bienvenue sur Digit BTP !');
    }

    private function storeBrandingFiles(OrganizationBranding $branding, Request $request, int $organizationId): void
    {
        $basePath = 'organizations/'.$organizationId;

        foreach (['logo' => 'logo', 'watermark' => 'watermark', 'signature' => 'signature'] as $field => $name) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store($basePath, 'public');
                $branding->{$name.'_path'} = $path;
            }
        }

        $branding->save();
    }
}

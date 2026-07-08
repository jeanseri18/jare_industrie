<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\CurrentOrganization;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register.equipe', [
            'organization' => CurrentOrganization::get(),
            'branding' => CurrentOrganization::branding(),
        ]);
    }

    public function register(Request $request)
    {
        $orgId = CurrentOrganization::id();

        $request->validate([
            'role' => 'required|in:dg,comptable,operateur,chef_commercial,admin_technique',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string|max:255',
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->where(fn ($q) => $q->where('organization_id', $orgId)),
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        $requiresDgValidation = $request->role !== User::ROLE_DG;

        $user = User::create([
            'organization_id' => $orgId,
            'name' => $request->nom.' '.$request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'requires_dg_validation' => $requiresDgValidation,
        ]);

        event(new Registered($user));

        if ($user->role !== User::ROLE_CLIENT && $user->requires_dg_validation && empty($user->email_verified_at)) {
            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte est en attente de validation par le DG.',
            ]);
        }

        Auth::login($user);

        return $this->redirectForRole($user);
    }

    public function showLoginForm()
    {
        return view('auth.login', [
            'organization' => CurrentOrganization::get(),
            'branding' => CurrentOrganization::branding(),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember' => 'boolean',
        ]);

        $orgId = CurrentOrganization::id();
        $credentials = $request->only('email', 'password');

        $userQuery = User::query()->where('email', $credentials['email']);

        if ($orgId) {
            $userQuery->where(function ($q) use ($orgId) {
                $q->where('organization_id', $orgId)
                    ->orWhere('role', User::ROLE_SUPER_ADMIN);
            });
        }

        $user = $userQuery->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Les identifiants fournis ne sont pas valides.']);
        }

        if ($user->role !== User::ROLE_CLIENT && $user->requires_dg_validation && empty($user->email_verified_at)) {
            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte est en attente de validation par le DG.',
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        if ($user->organization_id) {
            session(['organization_id' => $user->organization_id]);
        }

        return $this->redirectForRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function redirectForRole(User $user)
    {
        return match ($user->role) {
            User::ROLE_SUPER_ADMIN => redirect()->route('platform.organizations.index'),
            User::ROLE_DG => redirect()->route('dg.dashboard'),
            User::ROLE_COMPTABLE => redirect()->route('comptable.dashboard'),
            User::ROLE_OPERATEUR => redirect()->route('operateur.dashboard'),
            User::ROLE_CHEF_COMMERCIAL => redirect()->route('chef_commercial.dashboard'),
            User::ROLE_ADMIN_TECHNIQUE => redirect()->route('admin.dashboard'),
            User::ROLE_CLIENT => redirect()->route('client.dashboard'),
            default => redirect()->route('login'),
        };
    }
}

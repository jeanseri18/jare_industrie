<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'role' => 'required|in:dg,comptable,operateur,chef_commercial,admin_technique',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->nom . ' ' . $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Rediriger vers le tableau de bord approprié selon le rôle
        switch ($user->role) {
            case User::ROLE_DG:
                return redirect()->route('dg.dashboard');
            case User::ROLE_COMPTABLE:
                return redirect()->route('comptable.dashboard');
            case User::ROLE_OPERATEUR:
                return redirect()->route('operateur.dashboard');
            case User::ROLE_CHEF_COMMERCIAL:
                return redirect()->route('chef_commercial.dashboard');
            case User::ROLE_ADMIN_TECHNIQUE:
                return redirect()->route('admin.dashboard');
            case User::ROLE_CLIENT:
                return redirect()->route('client.dashboard');
            default:
                return redirect()->route('login');
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember' => 'boolean',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // Vérifier si l'utilisateur est un client
            $user = Auth::user();
            switch ($user->role) {
                case User::ROLE_DG:
                    return redirect()->route('dg.dashboard');
                case User::ROLE_COMPTABLE:
                    return redirect()->route('comptable.dashboard');
                case User::ROLE_OPERATEUR:
                    return redirect()->route('operateur.dashboard');
                case User::ROLE_CHEF_COMMERCIAL:
                    return redirect()->route('chef_commercial.dashboard');
                case User::ROLE_ADMIN_TECHNIQUE:
                    return redirect()->route('admin.dashboard');
                case User::ROLE_CLIENT:
                    return redirect()->route('client.dashboard');
                default:
                    return redirect()->route('login');
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne sont pas valides.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher le profil de l'utilisateur connecté (Staff)
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        $role = $user->role; // Supposons que le modèle User a un champ role

        // Déterminer le layout en fonction du rôle
        $layout = $this->getLayoutByRole($role);

        return view('profile.show', compact('user', 'layout'));
    }

    /**
     * Mettre à jour uniquement le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Votre mot de passe a été mis à jour avec succès.');
    }

    /**
     * Helper pour obtenir le layout selon le rôle
     */
    private function getLayoutByRole($role)
    {
        return match ($role) {
            'admin_technique' => 'layouts.admin',
            'dg' => 'layouts.dg',
            'comptable' => 'layouts.comptable',
            'chef_commercial' => 'layouts.chef_commercial',
            'operateur' => 'layouts.operateur',
            'client' => 'layouts.client',
            default => 'layouts.app',
        };
    }
}

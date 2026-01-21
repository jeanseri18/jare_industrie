<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EquipeController extends Controller
{
    /**
     * Affiche la liste des utilisateurs en attente d'activation.
     */
    public function index()
    {
        // Utilisateurs dont le compte n'est pas encore activé (email non vérifié)
        $users = User::whereNull('email_verified_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dg.equipes.index', compact('users'));
    }

    public function create()
    {
        return view('dg.equipes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|in:OPERATEUR,COMPTABLE,DG,ADMIN_TECHNIQUE',
            'password' => 'nullable|string|min:8',
        ]);

        $password = $validated['password'] ?? Str::random(12);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($password),
        ]);

        return redirect()->route('dg.equipes.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Active le compte d'un utilisateur.
     */
    public function activer(User $user)
    {
        $user->email_verified_at = now();
        $user->save();

        return back()->with('success', 'Compte activé avec succès.');
    }

    /**
     * Refuse la demande de création de compte (suppression de l'utilisateur).
     */
    public function refuser(User $user)
    {
        $user->delete();

        return back()->with('warning', 'Demande refusée et utilisateur supprimé.');
    }

    public function edit(User $user)
    {
        return view('dg.equipes.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:OPERATEUR,COMPTABLE,DG,ADMIN_TECHNIQUE',
            'password' => 'nullable|string|min:8',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return redirect()->route('dg.equipes.index')
            ->with('success', 'Informations utilisateur mises à jour avec succès.');
    }
}
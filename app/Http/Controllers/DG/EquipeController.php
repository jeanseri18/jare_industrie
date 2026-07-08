<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\CurrentOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EquipeController extends Controller
{
    /**
     * Affiche la liste des utilisateurs en attente d'activation.
     */
    public function index(Request $request)
    {
        $statut = $request->get('statut', 'en_attente');

        $orgId = CurrentOrganization::id();

        $query = User::query()
            ->where('role', '!=', 'client')
            ->when($orgId, fn ($q) => $q->where('organization_id', $orgId));

        if ($statut === 'en_attente') {
            $query->whereNull('email_verified_at')
                ->where('requires_dg_validation', true);
        } elseif ($statut === 'actif') {
            $query->where('requires_dg_validation', false);
        }

        // Filtre par nom
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filtre par date d'inscription
        if ($request->filled('date_inscription')) {
            $query->whereDate('created_at', $request->date_inscription);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(config('pagination.per_page'))->withQueryString();

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
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->where(fn ($q) => $q->where('organization_id', $orgId)),
            ],
            'role' => 'required|in:operateur,comptable,dg,admin_technique,chef_commercial',
            'password' => 'nullable|string|min:8',
        ]);

        $password = $validated['password'] ?? Str::random(12);

        User::create([
            'organization_id' => $orgId,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($password),
            'requires_dg_validation' => true,
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
        $user->requires_dg_validation = false;
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
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id)->where(fn ($q) => $q->where('organization_id', $user->organization_id)),
            ],
            'role' => 'required|in:operateur,comptable,dg,admin_technique,chef_commercial',
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

    /**
     * Affiche les logs (actions) liés à un utilisateur.
     */
    public function logs(User $user)
    {
        $activities = \App\Models\ActivityLog::with('user')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(config('pagination.per_page'))->withQueryString();

        return view('dg.equipes.logs', compact('user', 'activities'));
    }
}

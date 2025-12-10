<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'client')
            ->latest()
            ->paginate(10);
            
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'dg', 'comptable', 'chef_commercial', 'operateur'])],
            'telephone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'telephone' => $request->telephone,
        ]);

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'description' => "Création de l'utilisateur: {$user->name} ({$user->role})",
            'model_type' => User::class,
            'model_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès');
    }

    public function edit(User $user)
    {
        if ($user->role === 'client') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de modifier un client depuis cette interface');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role === 'client') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de modifier un client depuis cette interface');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => ['required', Rule::in(['admin', 'dg', 'comptable', 'chef_commercial', 'operateur'])],
            'telephone' => 'nullable|string|max:20',
        ]);

        $oldData = $user->toArray();
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'telephone' => $request->telephone,
        ]);

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'description' => "Modification des informations de l'utilisateur: {$user->name} ({$user->role})",
            'model_type' => User::class,
            'model_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Informations utilisateur mises à jour avec succès');
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'description' => "Modification du mot de passe de l'utilisateur: {$user->name}",
            'model_type' => User::class,
            'model_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Mot de passe mis à jour avec succès');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'client') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de supprimer un client depuis cette interface');
        }

        $userName = $user->name;
        $user->delete();

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'description' => "Suppression de l'utilisateur: {$userName}",
            'model_type' => User::class,
            'model_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès');
    }
}
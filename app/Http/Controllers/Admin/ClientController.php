<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->where('role', User::ROLE_CLIENT);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate(config('pagination.per_page'))->withQueryString();

        return view('admin.clients.index', compact('clients'));
    }

    public function editPassword(User $user)
    {
        if (!$user->isClient()) {
            return redirect()->route('admin.clients.index')
                ->with('error', 'Cet utilisateur n\'est pas un client.');
        }

        return view('admin.clients.password', compact('user'));
    }

    public function updatePassword(Request $request, User $user)
    {
        if (!$user->isClient()) {
            return redirect()->route('admin.clients.index')
                ->with('error', 'Cet utilisateur n\'est pas un client.');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => "Modification du mot de passe du client: {$user->name}",
            'model_type' => User::class,
            'model_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Mot de passe client mis à jour avec succès');
    }
}


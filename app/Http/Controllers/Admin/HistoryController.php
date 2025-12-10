<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        // Filtrer par utilisateur
        if ($request->filled('user')) {
            $query->where('user_id', $request->user);
        }

        // Filtrer par action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filtrer par plage de dates
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->latest()->paginate(20);
        $users = User::where('role', '!=', 'client')->get();

        return view('admin.history.index', compact('activities', 'users'));
    }

    public function clear()
    {
        ActivityLog::where('created_at', '<', now()->subMonths(6))->delete();

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'description' => 'Vidage de l\'historique des actions (plus de 6 mois)',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.history.index')
            ->with('success', 'Historique vidé avec succès (suppression des entrées de plus de 6 mois)');
    }
}
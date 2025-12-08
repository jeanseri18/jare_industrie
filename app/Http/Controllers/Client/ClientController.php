<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Document;
use App\Models\Notification;
use App\Models\Paiement;
use App\Models\Souscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientController extends \App\Http\Controllers\Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            abort(404, 'Client non trouvé');
        }

        $souscriptions = $client->souscriptions()->latest()->take(5)->get();
        $documents = $client->documents()->latest()->take(5)->get();
        $paiements = $client->paiements()->latest()->take(5)->get();
        $notifications = $client->notifications()->unread()->take(5)->get();

        return view('client.dashboard', compact('client', 'souscriptions', 'documents', 'paiements', 'notifications'));
    }

    public function documents()
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            abort(404, 'Client non trouvé');
        }

        $documents = $client->documents()
            ->latest()
            ->paginate(10);

        return view('client.documents.index', compact('documents'));
    }

    public function createDocument()
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            abort(404, 'Client non trouvé');
        }

        return view('client.documents.create', compact('client'));
    }

    public function storeDocument(Request $request)
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            abort(404, 'Client non trouvé');
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fichier' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        // Handle file upload
        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents/' . $client->id, $filename, 'public');
            
            $validated['chemin_fichier'] = $path;
            $validated['nom_fichier'] = $filename;
            $validated['type_fichier'] = $file->getClientOriginalExtension();
            $validated['taille_fichier'] = $file->getSize();
        }

        $validated['client_id'] = $client->id;
        $validated['date_upload'] = now();

        Document::create($validated);

        return redirect()->route('client.documents')->with('success', 'Document téléchargé avec succès');
    }

    public function destroyDocument(Document $document)
    {
        $client = Auth::user()->client;
        
        if (!$client || $document->client_id !== $client->id) {
            abort(403, 'Non autorisé');
        }

        // Delete the file from storage
        if ($document->chemin_fichier && file_exists(storage_path('app/public/' . $document->chemin_fichier))) {
            unlink(storage_path('app/public/' . $document->chemin_fichier));
        }

        $document->delete();

        return redirect()->route('client.documents')->with('success', 'Document supprimé avec succès');
    }

    public function paiements()
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            abort(404, 'Client non trouvé');
        }

        $paiements = $client->paiements()
            ->latest()
            ->paginate(10);

        return view('client.paiements.index', compact('paiements'));
    }

    public function createPaiement()
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            abort(404, 'Client non trouvé');
        }

        return view('client.paiements.create', compact('client'));
    }

    public function storePaiement(Request $request)
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            abort(404, 'Client non trouvé');
        }

        $validated = $request->validate([
            'montant' => 'required|numeric|min:0.01',
            'methode_paiement' => 'required|string|in:virement,carte,cash',
            'reference_paiement' => 'required|string|max:255',
            'observations' => 'nullable|string',
            'date_paiement' => 'required|date',
        ]);

        $validated['client_id'] = $client->id;
        $validated['user_id'] = Auth::id();
        $validated['statut'] = 'en_attente';
        $validated['type'] = 'frais_souscription';

        Paiement::create($validated);

        return redirect()->route('client.paiements')->with('success', 'Paiement enregistré avec succès. Il sera vérifié par notre équipe.');
    }

    public function notifications()
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            abort(404, 'Client non trouvé');
        }

        $notifications = $client->notifications()
            ->latest()
            ->paginate(15);

        return view('client.notifications.index', compact('notifications'));
    }

    public function markNotificationAsRead(Notification $notification)
    {
        $client = Auth::user()->client;
        
        if ($notification->client_id !== $client->id) {
            abort(403, 'Non autorisé');
        }

        $notification->update(['lu' => true]);

        return redirect()->back()->with('success', 'Notification marquée comme lue');
    }

    public function markAllNotificationsAsRead()
    {
        $client = Auth::user()->client;
        
        $client->notifications()->unread()->update(['lu' => true]);

        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

    public function profile()
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            abort(404, 'Client non trouvé');
        }

        return view('client.profile.show', compact('client'));
    }

    public function editProfile()
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            abort(404, 'Client non trouvé');
        }

        return view('client.profile.edit', compact('client'));
    }

    public function updateProfile(Request $request)
    {
        $client = Auth::user()->client;
        
        if (!$client) {
            abort(404, 'Client non trouvé');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string|max:500',
        ]);

        $client->update($validated);

        return redirect()->route('client.profile')->with('success', 'Profil mis à jour avec succès');
    }

    public function changePassword()
    {
        return view('client.profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('client.profile')->with('success', 'Mot de passe modifié avec succès');
    }
}
<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Souscription;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher le dashboard client avec informations, apport initial, frais de dossier et souscriptions
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Récupérer toutes les souscriptions du client
        $souscriptions = Souscription::with(['projet', 'paiements', 'attributionLot'])
            ->where('email', $user->email)
            ->orWhere('nom_prenom', 'LIKE', "%{$user->nom}%")
            ->latest()
            ->get();

        // Statistiques pour le dashboard
        $totalPaye = 0;
        foreach($souscriptions as $s) {
            $totalPaye += $s->paiements->where('statut', 'payé')->sum('montant');
        }

        $stats = [
            'total_souscriptions' => $souscriptions->count(),
            'total_paye' => $totalPaye,
            'nb_attributions' => $souscriptions->whereNotNull('attributionLot')->count(),
        ];

        return view('client.dashboard', compact('user', 'souscriptions', 'stats'));
    }

    /**
     * Helper pour générer les notifications dynamiques
     */
    private function getRecentNotifications($souscriptions)
    {
        $notifications = collect();

        foreach ($souscriptions as $s) {
            // Notification Attribution de lot
            if ($s->attributionLot) {
                $notifications->push((object)[
                    'titre' => 'Lot attribué !',
                    'message' => "Bonne nouvelle ! Le lot {$s->attributionLot->lot} (Îlot {$s->attributionLot->ilot}) vous a été attribué pour le projet {$s->projet->nom}.",
                    'date' => $s->attributionLot->created_at->format('d/m/Y'),
                    'relative' => $s->attributionLot->created_at->diffForHumans(),
                    'icon' => 'fas fa-home',
                    'color' => 'green'
                ]);
            }

            // Notifications Paiements récents
            foreach ($s->paiements->where('statut', 'payé')->sortByDesc('valide_at')->take(3) as $p) {
                $notifications->push((object)[
                    'titre' => 'Paiement validé',
                    'message' => "Votre paiement de " . number_format($p->montant, 0, ',', ' ') . " FCFA ({$p->type}) a été validé par la comptabilité.",
                    'date' => $p->valide_at->format('d/m/Y'),
                    'relative' => $p->valide_at->diffForHumans(),
                    'icon' => 'fas fa-check-circle',
                    'color' => 'blue'
                ]);
            }

            // Notification Dossier Soldé / Lettre Définitive
            if ($s->statut === 'SOLD') {
                $notifications->push((object)[
                    'titre' => 'Dossier Soldé',
                    'message' => "Félicitations ! Votre dossier {$s->ref_souscription} est entièrement soldé. Votre lettre définitive est en cours de génération.",
                    'date' => $s->updated_at->format('d/m/Y'),
                    'relative' => $s->updated_at->diffForHumans(),
                    'icon' => 'fas fa-certificate',
                    'color' => 'purple'
                ]);
            }
        }

        return $notifications->sortByDesc(function($n) {
            return \Carbon\Carbon::createFromFormat('d/m/Y', $n->date);
        });
    }

    /**
     * Afficher l'historique des paiements
     */
    public function historique()
    {
        $user = Auth::user();
        
        // Récupérer toutes les souscriptions du client
        $souscriptions = Souscription::where('email', $user->email)
            ->orWhere('nom_prenom', 'LIKE', "%{$user->nom}%")
            ->get();

        // Récupérer tous les paiements
        $paiements = Paiement::whereIn('dossier_id', $souscriptions->pluck('id'))
            ->with('souscription')
            ->latest('date_paiement')
            ->paginate(config('pagination.per_page'))->withQueryString();

        return view('client.historique', compact('paiements'));
    }

    /**
     * Afficher la liste des souscriptions du client
     */
    public function souscriptions()
    {
        $user = Auth::user();
        $souscriptions = Souscription::with(['projet', 'paiements', 'attributionLot'])
            ->where('email', $user->email)
            ->orWhere('nom_prenom', 'LIKE', "%{$user->nom}%")
            ->latest()
            ->paginate(config('pagination.per_page'))->withQueryString();

        return view('client.souscriptions', compact('souscriptions'));
    }

    /**
     * Afficher les documents du client
     */
    public function documents()
    {
        $user = Auth::user();
        $souscriptions = Souscription::with(['projet', 'attributionLot'])
            ->where('email', $user->email)
            ->orWhere('nom_prenom', 'LIKE', "%{$user->nom}%")
            ->latest()
            ->get();

        return view('client.documents', compact('souscriptions'));
    }

    /**
     * Afficher les notifications
     */
    public function notifications()
    {
        $user = Auth::user();
        
        // Récupérer les souscriptions du client
        $souscriptions = Souscription::where('email', $user->email)
            ->orWhere('nom_prenom', 'LIKE', "%{$user->nom}%")
            ->get();

        // Créer des notifications basées sur les événements
        $notifications = collect();

        // Notifications pour frais de dossier
        foreach ($souscriptions as $souscription) {
            $paiementsFraisDossier = Paiement::where('dossier_id', $souscription->id)
                ->where('type', 'frais_dossier')
                ->get();

            $totalPaye = $paiementsFraisDossier->where('statut', 'Soldé')->sum('montant');
            $fraisDossier = $souscription->frais_souscription ?? 0;

            if ($totalPaye < $fraisDossier) {
                $notifications->push((object)[
                    'id' => 'fd_' . $souscription->id,
                    'type' => 'paiement_frais',
                    'titre' => 'Paiement des frais de dossier',
                    'message' => "La comptabilité a traité 2.500 FCFA correspondant à votre frais de dossier pour un termement le 3-phase dans le cadre du projet Cité du Salut.",
                    'statut' => 'non_lu',
                    'date' => $souscription->created_at->format('d/m/Y'),
                    'relative' => 'il y a 3 jours',
                    'icon' => 'fas fa-exclamation-triangle',
                    'color' => 'orange'
                ]);
            }
        }

        // Notification pour échec de paiement
        $notifications->push((object)[
            'id' => 'ep_1',
            'type' => 'echec_paiement',
            'titre' => 'Échec de paiement',
            'message' => "Le paiement de 670.000 FCFA correspondant à votre apport initial n'a pas pu être pris en charge pour un termement de 3 phase dans le cadre de projet Cité du Salut",
            'statut' => 'non_lu',
            'date' => now()->subDays(2)->format('d/m/Y'),
            'relative' => 'il y a 2 jours',
            'icon' => 'fas fa-times-circle',
            'color' => 'red'
        ]);

        // Notification pour paiement réussi
        $paiementReussi = Paiement::whereIn('dossier_id', $souscriptions->pluck('id'))
            ->where('statut', 'Soldé')
            ->latest()
            ->first();

        if ($paiementReussi) {
            $notifications->push((object)[
                'id' => 'ps_' . $paiementReussi->id,
                'type' => 'paiement_succes',
                'titre' => 'Paiement de l\'apport initial avec succès',
                'message' => "Vous avez versé la somme de " . number_format($paiementReussi->montant, 0, ',', ' ') . " FCFA au titre de votre apport initial sur la logisteria à raison de 3 phase dans le cadre du projet Cité du Salut.",
                'statut' => 'lu',
                'date' => $paiementReussi->date_paiement->format('d/m/Y'),
                'relative' => 'il y a ' . $paiementReussi->date_paiement->diffInDays(now()) . ' jours',
                'icon' => 'fas fa-check-circle',
                'color' => 'blue'
            ]);
        }

        return view('client.notifications', compact('notifications'));
    }

    /**
     * Afficher le profil du client
     */
    public function profile()
    {
        $user = Auth::user();
        
        return view('client.profile', compact('user'));
    }

    /**
     * Mettre à jour le profil du client
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('client.profile')->with('success', 'Profil mis à jour avec succès');
    }

    /**
     * Mettre à jour le mot de passe
     */
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

<?php

namespace App\Observers;

use App\Models\Paiement;
use App\Models\Souscription;
use App\Services\ActivityLogger;

class PaiementObserver
{
    /**
     * Handle the Paiement "created" event.
     */
    public function created(Paiement $paiement): void
    {
        ActivityLogger::logModelEvent('created', $paiement, 'Paiement créé : ' . $paiement->reference . ' (' . $paiement->montant . '€)');
    }

    /**
     * Handle the Paiement "updated" event.
     */
    public function updated(Paiement $paiement): void
    {
        $changes = $paiement->getChanges();
        unset($changes['updated_at']);
        
        if (!empty($changes)) {
            ActivityLogger::logModelEvent('updated', $paiement, 'Paiement modifié : ' . $paiement->reference . ' (' . $paiement->montant . '€)');
            
            // Si le paiement passe à 'payé', vérifier si les frais de dossier sont complètement payés
            if (isset($changes['statut']) && $paiement->statut === 'payé') {
                $this->verifierFraisDossierComplet($paiement);
            }
        }
    }

    /**
     * Vérifie si les frais de dossier sont complètement payés et met à jour le statut de la souscription
     */
    private function verifierFraisDossierComplet(Paiement $paiement): void
    {
        $souscription = $paiement->souscription;
        
        if (!$souscription) {
            return;
        }
        
        // Vérifier si c'est un paiement de frais de dossier
        if ($paiement->type === 'FRAIS_DOSSIER' || $paiement->type === 'frais_dossier') {
            // Calculer le total des frais de dossier payés pour cette souscription
            $totalFraisDossierPaye = $souscription->paiements()
                ->whereIn('type', ['FRAIS_DOSSIER', 'frais_dossier'])
                ->where('statut', 'payé')
                ->sum('montant');
            
            // Si le montant total payé est égal ou supérieur aux frais de souscription
            if ($totalFraisDossierPaye >= $souscription->frais_souscription) {
                // Mettre à jour le statut de la souscription à 'valide'
                $souscription->update(['statut' => 'valide']);
                ActivityLogger::logModelEvent('updated', $souscription, 'Souscription passée à valide - Frais de dossier completement payés');
            }
        }
    }

    /**
     * Handle the Paiement "deleted" event.
     */
    public function deleted(Paiement $paiement): void
    {
        ActivityLogger::logModelEvent('deleted', $paiement, 'Paiement supprimé : ' . $paiement->reference . ' (' . $paiement->montant . '€)');
    }
}
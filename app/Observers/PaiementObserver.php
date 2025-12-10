<?php

namespace App\Observers;

use App\Models\Paiement;
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
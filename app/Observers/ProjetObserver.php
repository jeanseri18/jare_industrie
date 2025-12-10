<?php

namespace App\Observers;

use App\Models\Projet;
use App\Services\ActivityLogger;

class ProjetObserver
{
    /**
     * Handle the Projet "created" event.
     */
    public function created(Projet $projet): void
    {
        ActivityLogger::logModelEvent('created', $projet, 'Projet créé : ' . $projet->nom);
    }

    /**
     * Handle the Projet "updated" event.
     */
    public function updated(Projet $projet): void
    {
        $changes = $projet->getChanges();
        unset($changes['updated_at']);
        
        if (!empty($changes)) {
            ActivityLogger::logModelEvent('updated', $projet, 'Projet modifié : ' . $projet->nom);
        }
    }

    /**
     * Handle the Projet "deleted" event.
     */
    public function deleted(Projet $projet): void
    {
        ActivityLogger::logModelEvent('deleted', $projet, 'Projet supprimé : ' . $projet->nom);
    }
}
<?php

namespace App\Observers;

use App\Models\Souscription;
use App\Services\ActivityLogger;

class SouscriptionObserver
{
    /**
     * Handle the Souscription "created" event.
     */
    public function created(Souscription $souscription): void
    {
        ActivityLogger::logModelEvent('created', $souscription, 'Souscription créée : ' . $souscription->reference);
    }

    /**
     * Handle the Souscription "updated" event.
     */
    public function updated(Souscription $souscription): void
    {
        $changes = $souscription->getChanges();
        unset($changes['updated_at']);
        
        if (!empty($changes)) {
            ActivityLogger::logModelEvent('updated', $souscription, 'Souscription modifiée : ' . $souscription->reference);
        }
    }

    /**
     * Handle the Souscription "deleted" event.
     */
    public function deleted(Souscription $souscription): void
    {
        ActivityLogger::logModelEvent('deleted', $souscription, 'Souscription supprimée : ' . $souscription->reference);
    }
}
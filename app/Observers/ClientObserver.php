<?php

namespace App\Observers;

use App\Models\Client;
use App\Services\ActivityLogger;

class ClientObserver
{
    /**
     * Handle the Client "created" event.
     */
    public function created(Client $client): void
    {
        ActivityLogger::logModelEvent('created', $client, 'Client créé : ' . $client->nom . ' ' . $client->prenom);
    }

    /**
     * Handle the Client "updated" event.
     */
    public function updated(Client $client): void
    {
        $changes = $client->getChanges();
        unset($changes['updated_at']);
        
        if (!empty($changes)) {
            ActivityLogger::logModelEvent('updated', $client, 'Client modifié : ' . $client->nom . ' ' . $client->prenom);
        }
    }

    /**
     * Handle the Client "deleted" event.
     */
    public function deleted(Client $client): void
    {
        ActivityLogger::logModelEvent('deleted', $client, 'Client supprimé : ' . $client->nom . ' ' . $client->prenom);
    }
}
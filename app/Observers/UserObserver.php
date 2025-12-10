<?php

namespace App\Observers;

use App\Models\User;
use App\Services\ActivityLogger;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        ActivityLogger::logModelEvent('created', $user, 'Utilisateur créé : ' . $user->name);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $changes = $user->getChanges();
        unset($changes['updated_at']);
        
        if (!empty($changes)) {
            ActivityLogger::logModelEvent('updated', $user, 'Utilisateur modifié : ' . $user->name);
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        ActivityLogger::logModelEvent('deleted', $user, 'Utilisateur supprimé : ' . $user->name);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        ActivityLogger::logModelEvent('restored', $user, 'Utilisateur restauré : ' . $user->name);
    }
}
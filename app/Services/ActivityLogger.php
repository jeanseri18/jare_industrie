<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityLogger
{
    public static function log(string $action, string $description, ?Model $model = null, ?Request $request = null): ActivityLog
    {
        $request = $request ?: request();
        
        return ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }

    public static function logModelEvent(string $event, Model $model, ?string $description = null, ?Request $request = null): ActivityLog
    {
        $modelName = class_basename($model);
        $action = match($event) {
            'created' => 'create',
            'updated' => 'update',
            'deleted' => 'delete',
            default => $event
        };

        $description = $description ?? match($event) {
            'created' => "Création de {$modelName} #{$model->id}",
            'updated' => "Modification de {$modelName} #{$model->id}",
            'deleted' => "Suppression de {$modelName} #{$model->id}",
            default => "{$event} sur {$modelName} #{$model->id}"
        };

        return self::log($action, $description, $model, $request);
    }

    public static function logLogin(string $email, bool $success, ?Request $request = null): ActivityLog
    {
        return self::log(
            $success ? 'login' : 'login_failed',
            $success ? "Connexion réussie de {$email}" : "Échec de connexion pour {$email}",
            null,
            $request
        );
    }

    public static function logLogout(?Request $request = null): ActivityLog
    {
        return self::log(
            'logout',
            'Déconnexion de l\'utilisateur',
            null,
            $request
        );
    }
}
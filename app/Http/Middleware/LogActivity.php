<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ActivityLogger;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $action
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $action = 'access')
    {
        $response = $next($request);

        // Log l'activité après la réponse
        if (auth()->check()) {
            $description = $this->getDescription($request, $action);
            ActivityLogger::log($action, $description);
        }

        return $response;
    }

    /**
     * Get activity description
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $action
     * @return string
     */
    private function getDescription($request, $action)
    {
        $user = auth()->user();
        $route = $request->route()?->getName() ?? 'unknown';
        $method = $request->method();
        $path = $request->path();

        $descriptions = [
            'access' => "Accès à {$path}",
            'create' => "Création via {$method} {$path}",
            'update' => "Modification via {$method} {$path}",
            'delete' => "Suppression via {$method} {$path}",
            'export' => "Export depuis {$path}",
            'import' => "Import vers {$path}",
            'backup' => "Sauvegarde depuis {$path}",
            'restore' => "Restauration via {$path}",
        ];

        return $descriptions[$action] ?? "Action {$action} sur {$path}";
    }
}
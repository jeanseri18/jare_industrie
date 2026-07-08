<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Client;
use App\Models\Souscription;
use Illuminate\Http\Request;

trait SyncsIdentityExtensions
{
    protected function applyIdentityExtensionsToClient(Client $client, Request $request): void
    {
        $client->profession = $request->input('profession');
        $client->entreprise = $request->input('entreprise');
        $client->lieu_residence = $request->input('lieu_residence');
        $client->ville = $request->input('ville');
        $client->pays = $request->input('pays');
        $client->date_delivrance_piece = $request->filled('date_delivrance_piece') ? $request->input('date_delivrance_piece') : null;
        $client->date_expiration_piece = $request->filled('date_expiration_piece') ? $request->input('date_expiration_piece') : null;
    }

    protected function applyIdentityExtensionsToSouscription(Souscription $souscription, Request $request): void
    {
        $souscription->profession = $request->input('profession');
        $souscription->entreprise = $request->input('entreprise');
        $souscription->lieu_residence = $request->input('lieu_residence');
        $souscription->ville = $request->input('ville');
        $souscription->pays = $request->input('pays');
        $souscription->date_delivrance_piece = $request->filled('date_delivrance_piece') ? $request->input('date_delivrance_piece') : null;
        $souscription->date_expiration_piece = $request->filled('date_expiration_piece') ? $request->input('date_expiration_piece') : null;
    }
}

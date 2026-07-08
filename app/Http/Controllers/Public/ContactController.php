<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'entreprise' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:30',
            'message' => 'required|string|max:3000',
        ]);

        // TODO: envoyer par e-mail ou enregistrer en CRM
        logger()->info('Contact landing page', $validated);

        return redirect()->to(url('/#contact'))
            ->with('success', 'Merci ! Votre message a bien été envoyé. Nous vous recontacterons sous 48 h.');
    }
}

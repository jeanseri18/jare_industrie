@extends('layouts.dg')

@section('title', 'Décisions DG / Validation finale')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-check-circle me-2"></i>
            Décisions DG / Validation finale
        </div>
        <div>
            <a href="{{ route('dg.confirmation.index') }}" class="btn btn-outline-secondary"><i class="fas fa-list me-1"></i> Retour à la liste</a>
        </div>
    </div>

    <div style="padding: 20px;">
        @php
            $client = $souscription->client;
            $projet = $souscription->projet;
            $attrib = $souscription->attributionLot; // lot/ilot
            $validation = $validationFinale ?? null;

            // Préremplissage depuis la souscription et le client
            $nomPrenom = $client->nom_prenom ?? $souscription->nom_prenom;
            $dateNaissanceCarbon = ($client->date_naissance ?? $souscription->date_naissance);
            $dateNaissanceIso = optional($dateNaissanceCarbon)->format('Y-m-d');
            $lieuNaissance = $client->lieu_naissance ?? $souscription->lieu_naissance;
            $telephone = $souscription->telephone ?? $client->telephone ?? null;
            $email = $client->email ?? $souscription->email;
            $residence = null; // pas de champ source, sera saisi ou pris de ValidationFinale
            $profession = null; // pas de champ source, sera saisi ou pris de ValidationFinale

            // Montants et paiements
            $montantTotal = $montantTotalPaye ?? 0;
            $dateDernierPaiementCarbon = $dernierPaiement?->date_paiement;
            $dateDernierPaiementIso = optional($dateDernierPaiementCarbon)->format('Y-m-d');

            // Attribution
            $lot = $attrib->lot ?? '';
            $ilotAttribue = $attrib->ilot ?? '';

            // Si validation finale existe, surcharger les valeurs et mettre en lecture seule
            $isReadOnly = !empty($validation);
            if ($validation) {
                $nomPrenom = trim(($validation->nom_client ?? '') . ' ' . ($validation->prenom_client ?? '')) ?: $nomPrenom;
                $dateNaissanceIso = optional($validation->date_naissance)->format('Y-m-d') ?? $dateNaissanceIso;
                $lieuNaissance = $validation->lieu_naissance ?? $lieuNaissance;
                $telephone = $validation->numero_telephone ?? $telephone;
                $email = $validation->email ?? $email;
                $residence = $validation->lieu_residence ?? $residence;
                $profession = $validation->profession ?? $profession;
                $montantTotal = $validation->montant_total_paye ?? $montantTotal;
                $dateDernierPaiementIso = optional($validation->date_dernier_paiement)->format('Y-m-d') ?? $dateDernierPaiementIso;
                $lot = $validation->lot ?? $lot;
                $ilotAttribue = $validation->ilot_attribue ?? $ilotAttribue;
            }
        @endphp

        <form method="POST" action="{{ route('dg.souscriptions.validation-finale', $souscription) }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nom et Prénom du client</label>
                    <input type="text" name="nom_client" class="form-control" value="{{ $nomPrenom }}" {{ $isReadOnly ? 'readonly' : '' }}>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date et lieu de naissance</label>
                    <div class="d-flex gap-2">
                        <input type="date" name="date_naissance" class="form-control" value="{{ $dateNaissanceIso }}" {{ $isReadOnly ? 'readonly' : '' }}>
                        <input type="text" name="lieu_naissance" class="form-control" value="{{ $lieuNaissance }}" {{ $isReadOnly ? 'readonly' : '' }}>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Numéro de téléphone</label>
                    <input type="text" name="numero_telephone" class="form-control" value="{{ $telephone }}" {{ $isReadOnly ? 'readonly' : '' }}>
                </div>
                <div class="col-md-6">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" value="{{ $email }}" {{ $isReadOnly ? 'readonly' : '' }}>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Lieu de résidence</label>
                    <input type="text" name="lieu_residence" class="form-control" value="{{ $residence }}" {{ $isReadOnly ? 'readonly' : '' }}>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Profession</label>
                    <input type="text" name="profession" class="form-control" value="{{ $profession }}" {{ $isReadOnly ? 'readonly' : '' }}>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Projet</label>
                    <input type="text" class="form-control" value="{{ $projet?->nom }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Montant total payé</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="montant_total_paye" class="form-control" value="{{ $montantTotal }}" {{ $isReadOnly ? 'readonly' : '' }}>
                        <span class="input-group-text">FCFA</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date du dernier paiement</label>
                    <input type="date" name="date_dernier_paiement" class="form-control" value="{{ $dateDernierPaiementIso }}" {{ $isReadOnly ? 'readonly' : '' }}>
                </div>

                <div class="col-md-12">
                    <div class="d-flex gap-4 align-items-center">
                        <div>
                            <strong>Lot :</strong>
                            <input type="text" class="form-control d-inline-block w-auto" value="{{ $lot }}" readonly>
                            <input type="hidden" name="lot" value="{{ $lot }}">
                        </div>
                        <div>
                            <strong>Îlot attribué :</strong>
                            <input type="text" class="form-control d-inline-block w-auto" value="{{ $ilotAttribue }}" readonly>
                            <input type="hidden" name="ilot_attribue" value="{{ $ilotAttribue }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                @if($isReadOnly)
                    <a href="#" class="btn btn-secondary">Lettre déjà générée</a>
                @else
                    <button type="submit" class="btn btn-primary">Générer la lettre définitive</button>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
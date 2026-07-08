<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>État des Versements - {{ $souscription->ref_souscription }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 14px 18px;
            background-color: #fff;
            color: #000;
            font-size: 12px;
            position: relative;
        }
        /* filigrane (arrière-plan) — uniquement via branding tenant */
        body::before {
            content: none;
        }
        .container {
            width: 100%;
            max-width: 28cm; /* A4 Landscape width approx */
            margin: 0 auto;
            padding-bottom: 70px; /* Réserve l'espace pour le footer */
            box-sizing: border-box;
            position: relative;
            z-index: 1;
        }
        
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .logo-section {
            width: 180px;
        }
        .title-section {
            text-align: center;
            flex-grow: 1;
        }
        .company-name {
            font-size: 22px;
            font-weight: 900;
            color: #ff7200;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .company-subtitle {
            font-size: 15px;
            font-weight: 700;
            color: #004A80;
            text-transform: uppercase;
        }
        .qr-section {
            width: 100px;
            text-align: right;
        }

        /* Document Title */
        .doc-title-wrapper {
            text-align: center;
            margin: 6px 0 14px;
        }
        .doc-title {
            background-color: #ff7200;
            color: white;
            font-weight: bold;
            font-size: 18px;
            padding: 8px 22px;
            display: inline-block;
            border-radius: 2px;
            text-transform: uppercase;
        }

        /* Info Section */
        .info-container {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 12px;
        }
        .client-info {
            width: 60%;
        }
        .subscription-summary {
            width: 35%;
            padding: 6px 8px 8px;
            border-radius: 2px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
            align-items: baseline;
        }
        .label {
            font-weight: bold;
            width: 120px;
            flex-shrink: 0;
        }
        .value {
            flex-grow: 1;
            border-bottom: 1px dotted #999;
            padding-left: 5px;
        }
        .value.no-left-pad { padding-left: 0; }
        .inline-spacer { width: 18px; flex-shrink: 0; }
        .field {
            display: flex;
            align-items: baseline;
            flex: 1 1 auto;
            min-width: 0;
        }
        .field .label { width: auto; margin-right: 8px; }
        .field .value { min-width: 0; }

        .summary-title {
            border: 2px solid #004A80;
            color: #004A80;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            margin-bottom: 15px;
            font-size: 16px;
            text-transform: uppercase;
            background: #fff;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            table-layout: fixed;
        }
        th {
            border: 1px solid #000;
            padding: 8px 6px;
            text-align: center;
            font-weight: bold;
            background-color: #fff;
        }
        td {
            border: 1px solid #000;
            padding: 6px 6px;
            text-align: center;
        }
        tbody tr td { height: 26px; }
        td.text-left { text-align: left; padding-left: 14px; }
        tr.row-total td {
            font-weight: bold;
            background-color: #f3f4f6;
        }
        
        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #004A80;
            color: #fff;
            text-align: center;
            padding: 10px;
            font-size: 10px;
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #333;
            color: white;
            border: none;
            cursor: pointer;
            z-index: 1000;
        }

        @media print {
            .print-btn { display: none; }
            @page { margin: 0; size: landscape; }
            body { padding: 14px 18px; }
            .container { padding-bottom: 70px; }
            .footer { position: fixed; bottom: 0; }
        }
    </style>
    @include('documents.partials._brand_styles')
</head>
<body>
    @include('documents.partials._watermark')
    <button onclick="window.print()" class="print-btn">Imprimer</button>

    <div class="container">
        @php
            $logoSrc = $brand?->logoDataUri() ?? $brand?->logoAbsolutePath();
            $legalName = $brand?->displayName() ?? config('app.name');
            $tagline = $brand?->tagline ?? 'PROMOTEUR IMMOBILIER AGRÉÉ';
        @endphp
        <div class="header">
            <div class="logo-section">
                @if($logoSrc)
                    <img src="{{ $logoSrc }}" alt="" style="max-height: 80px;">
                @endif
            </div>
            <div class="title-section">
                <div class="company-name">{{ $legalName }}</div>
                <div class="company-subtitle">{{ $tagline }}</div>
            </div>
            <div class="qr-section">
                @php
                    $qrUrl = \Illuminate\Support\Facades\URL::signedRoute('public.souscriptions.etat-versements', ['souscription' => $souscription->id]);
                @endphp
                <img src="data:image/svg+xml;base64, {{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(80)->generate($qrUrl)) }}" >
            </div>
        </div>

        <div class="doc-title-wrapper">
            <div class="doc-title">ETAT DES VERSEMENTS</div>
        </div>

        <div class="info-container">
            <div class="client-info">
                <div class="info-row">
                    <span class="label">Nom</span>
                    <span class="value no-left-pad">{{ strtoupper($souscription->client->nom ?? '') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Prenom (s)</span>
                    <span class="value no-left-pad">{{ strtoupper($souscription->client->prenom ?? '') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Contacts</span>
                    <span class="value no-left-pad">{{ $souscription->client->telephone ?? $souscription->client->contact ?? '' }}</span>
                </div>
                <div class="info-row">
                    <div class="field">
                        <span class="label">Type de maison</span>
                        <span class="value no-left-pad">{{ $souscription->type_logement }}</span>
                    </div>
                    <span class="inline-spacer"></span>
                    <div class="field" style="max-width: 260px;">
                        <span class="label">Superficie terrain m2</span>
                        <span class="value no-left-pad">{{ $souscription->attributionLot->superficie ?? '' }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <span class="label">N° Villa</span>
                    <span class="value no-left-pad">{{ $souscription->attributionLot->numero_villa ?? '' }}</span>
                </div>
                <div class="info-row">
                    <div class="field">
                        <span class="label">Lot</span>
                        <span class="value no-left-pad">{{ $souscription->attributionLot->lot ?? '' }}</span>
                    </div>
                    <span class="inline-spacer"></span>
                    <div class="field" style="max-width: 260px;">
                        <span class="label">Ilot</span>
                        <span class="value no-left-pad">{{ $souscription->attributionLot->ilot ?? '' }}</span>
                    </div>
                </div>
            </div>

            <div class="subscription-summary">
                <div class="summary-title">SOUSCRIPTION</div>
                
                @php
                    $fraisDossier = \App\Models\FraisDossier::where('id_souscription', $souscription->id)->sum('montant');
                    $totalFacture = $souscription->prix_logement + $fraisDossier;
                    $totalVerse = $paiements->where('statut', 'payé')->sum('montant');
                    $solde = max($totalFacture - $totalVerse, 0);
                @endphp

                <div class="info-row">
                    <span class="label" style="width: 100px;">Total facture</span>
                    <span class="value no-left-pad">{{ number_format($totalFacture, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="info-row">
                    <span class="label" style="width: 100px;">Total versé</span>
                    <span class="value no-left-pad">{{ number_format($totalVerse, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="info-row">
                    <span class="label" style="width: 100px;">Solde à réglé</span>
                    <span class="value no-left-pad">{{ number_format($solde, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 25%;">Libellé règlement</th>
                    <th style="width: 13%;">Montant versé</th>
                    <th style="width: 14%;">Date de règlement</th>
                    <th style="width: 14%;">Mode de règlement</th>
                    <th style="width: 22%;">Référence chèque/virement</th>
                    <th style="width: 12%;">N° règlement</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $compteurApport = 1;
                    $compteurFrais = 1;
                    $compteurAutre = 1;
                    $paiementsPayes = $paiements->where('statut', 'payé')->sortBy('date_paiement')->values();
                    $totalMontantTable = (int) $paiementsPayes->sum('montant');
                @endphp
                @foreach($paiementsPayes as $paiement)
                    <tr>
                        <td class="text-left">
                            @if($paiement->type == 'APPORT')
                                ACOMPTE SUR AI N° {{ $compteurApport++ }}
                            @elseif($paiement->type == 'FRAIS_DOSSIER')
                                FRAIS DE DOSSIER {{ $compteurFrais > 1 ? 'N° '.$compteurFrais : '' }}
                                @php $compteurFrais++; @endphp
                            @elseif($paiement->type == 'PROJET')
                                VERSEMENT PROJET N° {{ $compteurAutre++ }}
                            @elseif($paiement->type == 'mensualite')
                                MENSUALITÉ
                            @else
                                {{ strtoupper(str_replace('_', ' ', $paiement->type)) }}
                            @endif
                        </td>
                        <td>{{ number_format($paiement->montant, 0, ',', ' ') }}</td>
                        <td>{{ $paiement->date_paiement ? $paiement->date_paiement->format('d/m/Y') : '-' }}</td>
                        <td>{{ strtoupper($paiement->mode) }}</td>
                        <td>{{ $paiement->reference_preuve ?? '-' }}</td>
                        <td>{{ $paiement->reference }}</td>
                    </tr>
                @endforeach
                <tr class="row-total">
                    <td class="text-left">TOTAL</td>
                    <td>{{ number_format($totalMontantTable, 0, ',', ' ') }}</td>
                    <td colspan="4"></td>
                </tr>
            </tbody>
        </table>
    </div>

    @include('documents.partials._pdf_footer')
</body>
</html>

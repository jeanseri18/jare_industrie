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
            padding: 20px;
            background-color: #fff;
            color: #000;
            font-size: 12px;
        }
        .container {
            width: 100%;
            max-width: 28cm; /* A4 Landscape width approx */
            margin: 0 auto;
        }
        
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .logo-section {
            width: 200px;
        }
        .title-section {
            text-align: center;
            flex-grow: 1;
        }
        .company-name {
            font-size: 24px;
            font-weight: 900;
            color: #E30613;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .company-subtitle {
            font-size: 16px;
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
            margin-bottom: 30px;
        }
        .doc-title {
            background-color: #E30613;
            color: white;
            font-weight: bold;
            font-size: 18px;
            padding: 8px 20px;
            display: inline-block;
            border-radius: 5px;
            text-transform: uppercase;
            box-shadow: 3px 3px 0px #ccc;
        }

        /* Info Section */
        .info-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .client-info {
            width: 60%;
        }
        .subscription-summary {
            width: 35%;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
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

        .summary-title {
            border: 2px solid #004A80;
            color: #004A80;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            margin-bottom: 15px;
            font-size: 16px;
            text-transform: uppercase;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            background-color: #fff;
        }
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
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
            @page { margin: 1cm; size: landscape; }
            body { padding: 0; }
            .footer { position: fixed; bottom: 0; }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn">Imprimer</button>

    <div class="container">
        <div class="header">
            <div class="logo-section">
                <img src="{{ asset('LOGO.png') }}" alt="JARE INDUSTRIES" style="max-height: 80px;">
            </div>
            <div class="title-section">
                <div class="company-name">JARE INDUSTRIES CÔTE D'IVOIRE</div>
                <div class="company-subtitle">PROMOTEUR IMMOBILIER AGRÉÉ</div>
            </div>
            <div class="qr-section">
                <img src="data:image/svg+xml;base64, {{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(80)->generate($souscription->ref_souscription)) }}" >
            </div>
        </div>

        <div class="doc-title-wrapper">
            <div class="doc-title">ETAT DES VERSEMENTS</div>
        </div>

        <div class="info-container">
            <div class="client-info">
                <div class="info-row">
                    <span class="label">Nom</span>
                    <span class="value">: {{ strtoupper($souscription->client->nom ?? '') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Prenom (s)</span>
                    <span class="value">: {{ strtoupper($souscription->client->prenom ?? '') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Contacts</span>
                    <span class="value">: {{ $souscription->client->telephone ?? $souscription->client->contact ?? '' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Type de maison</span>
                    <span class="value">: {{ $souscription->type_logement }}</span>
                    <span class="label" style="width: auto; margin-left: 20px; margin-right: 5px;">Superficie terrain m2</span>
                    <span class="value" style="width: 80px; flex-grow: 0;">: {{ $souscription->attributionLot->superficie ?? '...' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">N° Villa</span>
                    <span class="value">: {{ $souscription->attributionLot->numero_villa ?? '...' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Lot</span>
                    <span class="value">: {{ $souscription->attributionLot->lot ?? '...' }}</span>
                    <span class="label" style="width: auto; margin-left: 20px; margin-right: 5px;">Ilot</span>
                    <span class="value" style="width: 80px; flex-grow: 0;">: {{ $souscription->attributionLot->ilot ?? '...' }}</span>
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
                    <span class="value">: {{ number_format($totalFacture, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="info-row">
                    <span class="label" style="width: 100px;">Total versé</span>
                    <span class="value">: {{ number_format($totalVerse, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="info-row">
                    <span class="label" style="width: 100px;">Solde à réglé</span>
                    <span class="value">: {{ number_format($solde, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Libellé règlement</th>
                    <th>Montant versé</th>
                    <th>Date de règlement</th>
                    <th>Mode de règlement</th>
                    <th>Référence chèque/virement</th>
                    <th>N° règlement</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $compteurApport = 1;
                    $compteurFrais = 1;
                    $compteurAutre = 1;
                @endphp
                @foreach($paiements->where('statut', 'payé')->sortBy('date_paiement') as $paiement)
                    <tr>
                        <td style="text-align: left; padding-left: 20px;">
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
                
                {{-- Empty rows to fill the page if needed --}}
                @for($i = 0; $i < max(10 - $paiements->where('statut', 'payé')->count(), 0); $i++)
                    <tr>
                        <td style="height: 25px;"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <div class="footer">
        Siège Social : Abidjan Cocody - 2 Plateaux Macaci non loin de la Pharmacie ORCHID - Cité Sicogi Villa 280 Lot 53 - ilot 19<br>
        28 B.P .70 ABIDJAN 28 - Tel : +225 21 20 80 54 20 / 07 03 94 03 14 - Whatsapp : +225 07 03 94 03 14 / 07 12 42 42 42<br>
        RCCM : CI-ABJ-03-2023-M-11022 - CC N° : 1517590M - E-mail- : emmanuela.kore@jare-indudtries.com
    </div>
</body>
</html>

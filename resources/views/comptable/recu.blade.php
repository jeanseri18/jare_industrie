<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement - {{ $paiement->reference }}</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 0;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #000;
            font-size: 14px;
        }
        .container {
            width: 100%;
            height: 100vh; /* Full height for A5 landscape if printed as such */
            max-width: 21cm; /* Approx A4 width if printed on A4 */
            margin: 0 auto;
            position: relative;
            background-color: #fff;
            padding: 20px 40px;
            box-sizing: border-box;
        }
        
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #E30613;
        }
        .logo-section {
            width: 150px;
        }
        .logo-img {
            max-width: 100%;
            height: auto;
        }
        .title-section {
            text-align: center;
            flex-grow: 1;
            padding: 0 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: 900;
            color: #E30613; /* Red */
            text-transform: uppercase;
            line-height: 1.2;
            margin-bottom: 5px;
        }
        .company-subtitle {
            font-size: 18px;
            font-weight: 700;
            color: #004A80; /* Blue */
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .qr-section {
            width: 100px;
            text-align: right;
        }
        
        /* Info Bar */
        .info-bar {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 25px;
            font-size: 16px;
            font-weight: bold;
        }
        .recu-num {
            font-size: 22px;
            font-weight: 800;
        }
        .bpf {
            font-size: 18px;
        }
        
        /* Form Rows */
        .form-row {
            display: flex;
            align-items: baseline;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .label {
            font-weight: 800;
            margin-right: 10px;
            white-space: nowrap;
        }
        .value {
            flex-grow: 1;
            border-bottom: 1px dotted #999;
            padding-left: 10px;
            font-weight: 600;
            color: #333;
        }
        .multi-col-row {
            display: flex;
            justify-content: space-between;
        }
        .col-item {
            display: flex;
            align-items: baseline;
        }
        
        /* Signature */
        .signature-section {
            margin-top: 40px;
            text-align: right;
            padding-right: 20px;
        }
        .signature-text {
            display: inline-block;
            text-decoration: underline;
            font-weight: normal;
            font-size: 16px;
        }
        
        /* Footer */
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #2c3e50; /* Dark Blue from image */
            color: #fff;
            text-align: center;
            padding: 10px 20px;
            font-size: 10px;
            line-height: 1.4;
        }
        
        /* Watermark */
        .watermark-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60%;
            opacity: 0.05;
            z-index: 0;
            pointer-events: none;
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
            .container { 
                border: none; 
                width: 100%; 
                height: 100%;
                margin: 0; 
                padding: 20px 40px;
            }
            body { background: white; }
            @page { margin: 0; }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn">Imprimer</button>

    <div class="container">
        <!-- Background Logo/Watermark if needed, represented by text or image -->
        <!-- <img src="/path/to/logo.png" class="watermark-bg" /> -->
        
        <div class="header">
            <div class="logo-section">
                <!-- Placeholder for Logo -->
                <img src="{{ asset('LOGO.png') }}" alt="JARE INDUSTRIES" style="max-height: 80px; max-width: 100%;">
                @if(!file_exists(public_path('LOGO.png')))
                <div style="font-weight:bold; font-size:20px; line-height:1; color:#E30613;">
                    <span style="font-size:30px;">🏠</span><br>JARE<br><span style="font-size:12px; color:#333;">INDUSTRIES</span>
                </div>
                @endif
            </div>
            <div class="title-section">
                <div class="company-name">JARE INDUSTRIES CÔTE D'IVOIRE</div>
                <div class="company-subtitle">PROMOTEUR IMMOBILIER AGRÉÉ</div>
            </div>
            <div class="qr-section">
                <!-- QR Code placeholder -->
                <img src="data:image/svg+xml;base64, {{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(80)->generate($paiement->reference)) }}" >
            </div>
        </div>

        <div class="info-bar">
            <div class="recu-num">REÇU N° {{ $paiement->reference }}</div>
            <div class="bpf">BPF : {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</div>
            <div>Abidjan, le {{ $paiement->date_paiement ? $paiement->date_paiement->format('d/m/Y') : now()->format('d/m/Y') }}</div>
        </div>

        <div class="form-row">
            <span class="label">M, MME, MLLE</span>
            <span class="label">:</span>
            <span class="value">{{ strtoupper(($paiement->souscription->client->nom ?? '') . ' ' . ($paiement->souscription->client->prenom ?? '')) }}</span>
        </div>

        <div class="form-row">
            <span class="label">Type de logement</span>
            <span class="label">:</span>
            <span class="value">{{ $paiement->souscription->type_logement ?? 'Non spécifié' }}</span>
        </div>

        <div class="form-row multi-col-row">
            <div class="col-item" style="flex: 1;">
                <span class="label">N° Villa</span>
                <span class="label">:</span>
                <span class="value">{{ $paiement->souscription->attributionLot->numero_villa ?? '.....' }}</span>
            </div>
            <div class="col-item" style="flex: 1; margin-left: 20px;">
                <span class="label">N° Lot</span>
                <span class="value">{{ $paiement->souscription->attributionLot->lot ?? '.....' }}</span>
            </div>
            <div class="col-item" style="flex: 1; margin-left: 20px;">
                <span class="label">N° ILot</span>
                <span class="value">{{ $paiement->souscription->attributionLot->ilot ?? '.....' }}</span>
            </div>
        </div>

        <div class="form-row">
            <span class="label">Montant en lettre</span>
            <span class="label">:</span>
            <span class="value">
                @php
                    echo ucfirst(numberToWords($paiement->montant)) . " Francs CFA";
                @endphp
            </span>
        </div>

        <div class="form-row">
            <span class="label">Pour le Motif</span>
            <span class="label">:</span>
            <span class="value">
                @if($paiement->type == 'FRAIS_DOSSIER') Frais de Dossier
                @elseif($paiement->type == 'APPORT') Apport Initial
                @elseif($paiement->type == 'PROJET') Versement Projet
                @elseif($paiement->type == 'REMBOURSEMENT') Remboursement
                @else {{ ucfirst(str_replace('_', ' ', $paiement->type)) }}
                @endif
            </span>
        </div>

        <div class="form-row">
            <span class="label">Reste à payer</span>
            <span class="label">:</span>
            <span class="value">
                @php
                    $prixLogement = $paiement->souscription->prix_logement ?? 0;
                    $fraisDossier = \App\Models\FraisDossier::where('id_souscription', $paiement->souscription->id)->sum('montant');
                    $totalAttendu = $prixLogement + $fraisDossier;
                    
                    // Calcul du total payé
                    $totalPaye = $paiement->souscription->paiements()
                        ->where('statut', 'payé')
                        ->sum('montant');
                        
                    $reste = max($totalAttendu - $totalPaye, 0);
                @endphp
                {{ number_format($reste, 0, ',', ' ') }} FCFA
            </span>
        </div>

        <div class="signature-section">
            <div class="signature-text">Signature Jare Industries</div>
        </div>

        <div class="footer">
            Siège Social : Abidjan Cocody - 2 Plateaux Macaci non loin de la Pharmacie ORCHID - Cité Sicogi Villa 280 Lot 53 - ilot 19<br>
            28 B.P .70 ABIDJAN 28 - Tel : +225 21 20 80 54 20 / 07 03 94 03 14 - Whatsapp : +225 07 03 94 03 14 / 07 12 42 42 42<br>
            RCCM : CI-ABJ-03-2023-M-11022 - CC N° : 1517590M - E-mail- : emmanuela.kore@jare-industries.com
        </div>
    </div>
</body>
</html>

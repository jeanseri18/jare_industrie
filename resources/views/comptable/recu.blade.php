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
        html, body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
            color: #111;
            font-size: 12px;
        }
        .page {
            position: relative;
            padding: 10px 16px 62px; /* réserve de place pour le footer fixe */
            box-sizing: border-box;
        }
        .watermark {
            position: fixed;
            left: 50%;
            top: 52%;
            transform: translate(-50%, -50%);
            width: 65%;
            opacity: 0.07;
            z-index: 0;
            text-align: center;
        }
        .watermark img { width: 100%; height: auto; }
        .content { position: relative; z-index: 1; }
        
        .header-table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: middle; }
        .logo-cell { width: 22%; }
        .title-cell { width: 56%; text-align: center; }
        .qr-cell { width: 22%; text-align: right; }
        .company-name { font-size: 26px; font-weight: 900; color: #ff7200; text-transform: uppercase; margin: 0; }
        .company-subtitle { font-size: 18px; font-weight: 900; color: #004A80; text-transform: uppercase; letter-spacing: 2px; margin: 2px 0 0; }
        .red-line { height: 3px; background: #ff7200; margin: 6px 0 12px; }
        
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .info-table td { font-weight: 900; font-size: 18px; vertical-align: baseline; }
        .info-left { width: 34%; }
        .info-center { width: 33%; text-align: center; font-size: 14px; font-weight: 900; }
        .info-right { width: 33%; text-align: right; font-size: 14px; font-weight: 900; }
        
        .form-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .form-table td { padding: 10px 0; vertical-align: baseline; }
        .lbl { width: 190px; font-weight: 900; }
        .colon { width: 18px; font-weight: 900; }
        .dots { border-bottom: 2px dotted #6b7280; height: 18px; }
        .dots span { display: inline-block; padding-left: 10px; font-weight: 700; color: #111; }
        .triple { width: 100%; border-collapse: collapse; }
        .triple td { padding: 0; }
        .triple .item-lbl { width: 110px; font-weight: 900; }
        .triple .item-colon { width: 14px; font-weight: 900; }
        .triple .item-dots { border-bottom: 2px dotted #6b7280; height: 18px; }
        .triple .item-dots span { padding-left: 10px; font-weight: 700; }
        
        .signature-wrap { margin-top: 18px; }
        .signature-text { text-align: right; font-size: 14px; text-decoration: underline; padding-right: 8px; }
        
        .footer {
            background-color: #2c3e50; /* Dark Blue from image */
            color: #fff;
            text-align: center;
            padding: 7px 14px;
            font-size: 8.5px;
            line-height: 1.25;
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            border-top: 3px solid #ff7200;
            height: 44px;
            box-sizing: border-box;
        }

        @media (max-width: 768px) {
            .footer {
                display: none;
            }
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
            .print-btn { display: none !important; }
            body {
                background: white;
                margin: 0;
                padding: 0;
            }
            @page { margin: 0; }
        }
    </style>
    @include('documents.partials._brand_styles')
</head>
<body>
    @include('documents.partials._watermark')
    @if(empty($asPdf))
        <button onclick="window.print()" class="print-btn">Imprimer</button>
        @if(!empty($paiement->preuve_paiement))
            @php
                $preuveUrl = \Illuminate\Support\Facades\URL::signedRoute('public.paiements.preuve', ['paiement' => $paiement->id]);
            @endphp
            <a class="print-btn" href="{{ $preuveUrl }}" target="_blank" rel="noopener" style="right: 140px; background:#6c757d; text-decoration:none; display:inline-block;">
                Voir la preuve
            </a>
        @endif
    @endif

    @php
        $nomClient = strtoupper(trim(($paiement->souscription->client->nom ?? '') . ' ' . ($paiement->souscription->client->prenom ?? '')));
        $motif = '';
        if ($paiement->type == 'FRAIS_DOSSIER') $motif = 'Frais de Dossier';
        elseif ($paiement->type == 'APPORT') $motif = 'Apport Initial';
        elseif ($paiement->type == 'PROJET') $motif = 'Versement Projet';
        elseif ($paiement->type == 'REMBOURSEMENT') $motif = 'Remboursement';
        else $motif = ucfirst(str_replace('_', ' ', (string) $paiement->type));

        $prixLogement = $paiement->souscription->prix_logement ?? 0;
        $fraisDossier = \App\Models\FraisDossier::where('id_souscription', $paiement->souscription->id)->sum('montant');
        $totalAttendu = $prixLogement + $fraisDossier;
        $totalPaye = $paiement->souscription->paiements()->where('statut', 'payé')->sum('montant');
        $reste = max($totalAttendu - $totalPaye, 0);

        $dateTxt = $paiement->date_paiement ? $paiement->date_paiement->format('d/m/Y') : now()->format('d/m/Y');
    @endphp

    @php
        $logoSrc = $brand?->logoDataUri() ?? $brand?->logoAbsolutePath();
        $legalName = $brand?->displayName() ?? config('app.name');
        $tagline = $brand?->tagline ?? 'PROMOTEUR IMMOBILIER AGRÉÉ';
        $directorName = $brand?->director_name ?? 'Directeur Général';
        $signatureSrc = $brand?->signatureDataUri() ?? $brand?->signatureAbsolutePath();
    @endphp

    <div class="page">
        <div class="content">
            <table class="header-table">
                <tr>
                    <td class="logo-cell">
                        @if($logoSrc)
                            <img src="{{ $logoSrc }}" alt="" style="max-height: 70px; max-width: 140px;">
                        @endif
                    </td>
                    <td class="title-cell">
                        <div class="company-name">{{ $legalName }}</div>
                        <div class="company-subtitle">{{ $tagline }}</div>
                    </td>
                    <td class="qr-cell">
                        @php
                            $qrUrl = \Illuminate\Support\Facades\URL::signedRoute('public.paiements.recu', ['paiement' => $paiement->id]);
                        @endphp
                        <img src="data:image/svg+xml;base64,{{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(86)->margin(0)->generate($qrUrl)) }}" style="width:78px;height:78px;">
                    </td>
                </tr>
            </table>

            <div class="red-line"></div>

            <table class="info-table">
                <tr>
                    <td class="info-left">REÇU N°</td>
                    <td class="info-center">BPF : {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                    <td class="info-right">Abidjan, le {{ $dateTxt }}</td>
                </tr>
            </table>

            <table class="form-table">
                <tr>
                    <td class="lbl">M, MME, MLLE</td>
                    <td class="colon">:</td>
                    <td class="dots"><span>{{ $nomClient ?: '' }}</span></td>
                </tr>
                <tr>
                    <td class="lbl">Type de logement</td>
                    <td class="colon">:</td>
                    <td class="dots"><span>{{ $paiement->souscription->type_logement ?? '' }}</span></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <table class="triple">
                            <tr>
                                <td class="item-lbl">N° Villa</td>
                                <td class="item-colon">:</td>
                                <td class="item-dots" style="width: 33%;"><span>{{ $paiement->souscription->attributionLot->numero_villa ?? '' }}</span></td>
                                <td style="width: 22px;"></td>
                                <td class="item-lbl">N° Lot</td>
                                <td class="item-colon">:</td>
                                <td class="item-dots" style="width: 33%;"><span>{{ $paiement->souscription->attributionLot->lot ?? '' }}</span></td>
                                <td style="width: 22px;"></td>
                                <td class="item-lbl">N° ILot</td>
                                <td class="item-colon">:</td>
                                <td class="item-dots" style="width: 33%;"><span>{{ $paiement->souscription->attributionLot->ilot ?? '' }}</span></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="lbl">Montant en lettre</td>
                    <td class="colon">:</td>
                    <td class="dots">
                        <span>
                            @php
                                echo ucfirst(numberToWords($paiement->montant)) . " Francs CFA";
                            @endphp
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="lbl">Pour le Motif</td>
                    <td class="colon">:</td>
                    <td class="dots"><span>{{ $motif }}</span></td>
                </tr>
                <tr>
                    <td class="lbl">Reste à payer</td>
                    <td class="colon">:</td>
                    <td class="dots"><span>{{ number_format($reste, 0, ',', ' ') }} FCFA</span></td>
                </tr>
            </table>

            <div class="signature-wrap">
                @if($signatureSrc)
                    <div style="text-align: right; margin-bottom: 6px;">
                        <img src="{{ $signatureSrc }}" alt="" style="max-height: 60px;">
                    </div>
                @endif
                <div class="signature-text">Signature {{ $legalName }} — {{ $directorName }}</div>
            </div>
        </div>
    </div>

    @include('documents.partials._pdf_footer')
</body>
</html>

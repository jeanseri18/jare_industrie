<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attestation de Réservation</title>
    <style>
        @page {
            margin: 0.5cm 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px; /* Slightly smaller to fit content */
            line-height: 1.3;
            color: #000;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .logo-cell {
            width: 20%;
            vertical-align: top;
        }
        .title-cell {
            width: 60%;
            text-align: center;
            vertical-align: top;
            padding-top: 10px;
        }
        .qr-cell {
            width: 20%;
            text-align: right;
            vertical-align: top;
        }
        .company-name {
            font-size: 22px;
            font-weight: 900;
            color: #E30613;
            text-transform: uppercase;
            margin: 0;
        }
        .company-sub {
            font-size: 14px;
            font-weight: 700;
            color: #004A80;
            text-transform: uppercase;
            margin: 0;
        }
        .doc-title-box {
            background-color: #E30613;
            color: white;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            padding: 5px 10px;
            border-radius: 10px;
            margin: 10px auto;
            width: 80%;
            text-transform: uppercase;
            border: 2px solid #004A80;
        }
        .ref-number {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            color: #004A80;
            margin-bottom: 10px;
        }
        .intro-text {
            text-align: justify;
            margin-bottom: 10px;
            font-size: 10px;
        }
        .certify-header {
            text-align: center;
            font-weight: bold;
            color: #004A80;
            text-transform: uppercase;
            margin: 10px 0;
            font-size: 12px;
        }
        .client-info-table {
            width: 100%;
            margin-bottom: 10px;
            font-size: 11px;
        }
        .client-info-table td {
            padding: 3px 0;
        }
        .label {
            font-weight: bold;
        }
        .dots {
            border-bottom: 1px dotted #000;
            display: inline-block;
            width: 100%;
        }
        .project-header {
            text-align: center;
            font-weight: bold;
            color: #004A80;
            margin: 10px 0;
            font-size: 12px;
        }
        .villa-desc {
            color: #004A80; /* Light blue */
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .amenities-list {
            list-style-type: none; /* Custom bullets manually */
            padding-left: 0;
            margin: 0;
        }
        .amenities-list li {
            margin-bottom: 2px;
            padding-left: 15px;
            position: relative;
        }
        .amenities-list li:before {
            content: "▶";
            position: absolute;
            left: 0;
            color: #004A80;
            font-size: 10px;
        }
        .land-title-info {
            text-align: center;
            font-weight: bold;
            color: #004A80;
            margin: 10px 0;
            font-size: 11px;
        }
        .financial-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .financial-table td {
            padding: 4px 0;
        }
        .closing-text {
            text-align: center;
            font-weight: bold;
            color: #004A80;
            margin: 15px 0;
        }
        .signatures-table {
            width: 100%;
            margin-top: 10px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #004A80;
            color: white;
            text-align: center;
            font-size: 8px;
            padding: 5px;
            line-height: 1.2;
        }
        .page-content {
            padding-bottom: 40px; /* Space for footer */
        }
    </style>
</head>
<body>
    <div class="page-content">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if(file_exists(public_path('LOGO.png')))
                        <img src="{{ public_path('LOGO.png') }}" style="max-width: 100px;">
                    @else
                        <div style="color:red; font-weight:bold;">JARE<br>INDUSTRIES</div>
                    @endif
                </td>
                <td class="title-cell">
                    <div class="company-name">JARE INDUSTRIES</div>
                    <div class="company-sub">PROMOTEUR IMMOBILIER AGRÉÉ</div>
                    <div style="font-size: 10px; font-weight: 700; color: #004A80; margin-top: 4px;">
                        N° d’agrément du promoteur : {{ $souscription->projet->numero_agrement ?? '—' }}
                    </div>
                </td>
                <td class="qr-cell">
                    <img src="data:image/svg+xml;base64, {{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(80)->generate($souscription->ref_souscription)) }}" >
                </td>
            </tr>
        </table>

        <div class="doc-title-box">
            ATTESTATION DE RESERVATION DE LOGEMENT
        </div>

        <div class="ref-number">
            N° RKDG_2026/GDA/{{ $souscription->id }}/{{ $souscription->attributionLot->ilot ?? '...' }}-L{{ $souscription->attributionLot->lot ?? '...' }}
        </div>

        <div class="intro-text">
            Je soussigné, Monsieur <strong>Roland KOUAKOU</strong>, Directeur Général de la société <strong>JARE INDUSTRIES</strong>,
            <strong>Promoteur Immobilier Agréé sous le N°{{ $souscription->projet->numero_agrement ?? '—' }}</strong>. Ayant son siège social
            à ABIDJAN COCODY, LES DEUX PLATEAUX, MACACI, Villa 150, lot 53, Boulevard latrille, 28 Boîte Postale 70
            ABIDJAN 28 République de COTE D'IVOIRE.<br>
            La Société <strong>JARE INDUSTRIES</strong> réalise en ce moment une cité de <strong>406</strong> logements dénommée <strong>« {{ strtoupper($souscription->projet->nom ?? 'CITE ALAIN - RICHARD DONWAHI') }} »</strong> à Ebimpé dans la commune d'Anyama objet de l'agrément Programme numéro :
            <strong>22-00014/MCLU/DGLCV/CAPPI/ogla du 21 avril 2022</strong> délivré par le ministère de la construction du logement et de
            l'urbanisme.
        </div>

        <div class="certify-header">
            CERTIFIE ET ATTESTE, PAR LES PRÉSENTES, QUE :
        </div>

        <table class="client-info-table">
            <tr>
                <td style="width: 120px;"><span class="label">Nom & Prénoms :</span></td>
                <td style="border-bottom: 1px dotted #000;">{{ strtoupper(($souscription->client->nom ?? '') . ' ' . ($souscription->client->prenom ?? '')) }}</td>
            </tr>
            <tr>
                <td><span class="label">Fonction :</span></td>
                <td style="border-bottom: 1px dotted #000;">{{ $souscription->client->profession ?? '..................................................................................' }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Né le :</span> 
                    <span style="border-bottom: 1px dotted #000; padding: 0 10px;">{{ ($souscription->client->date_naissance ?? $souscription->date_naissance) ? ($souscription->client->date_naissance ?? $souscription->date_naissance)->format('d/m/Y') : '...../...../.......' }}</span>
                    <span class="label" style="margin-left: 10px;">à</span>
                    <span style="border-bottom: 1px dotted #000; padding: 0 10px; width: 150px; display:inline-block;">{{ $souscription->client->lieu_naissance ?? $souscription->lieu_naissance ?? '...................' }}</span>
                    <span class="label" style="margin-left: 10px;">République de Côte d'Ivoire,</span>
                </td>
            </tr>
            <tr>
                <td><span class="label">De nationalité :</span></td>
                <td style="border-bottom: 1px dotted #000;">{{ $souscription->client->nationalite ?? $souscription->nationalite ?? '..................................................................................' }},</td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">CNI/ N°</span>
                    <span style="border-bottom: 1px dotted #000; padding: 0 10px; width: 150px; display:inline-block;">{{ $souscription->client->numero_piece ?? $souscription->numero_piece ?? '.............................' }}</span>
                    {{-- ligne de validité commentée à la demande --}}
                </td>
            </tr>
        </table>

        <div class="project-header">
            Est réservataire dans la {{ $souscription->projet->nom ?? 'Cité Alain-Richard DONWAHI 1' }} à Ebimpé Anyama de
        </div>

        <div class="villa-desc">
            La {{ $souscription->type_logement ?? 'villa Basse 3 Pièces Autonome' }} aux Commodités suivantes :
        </div>

        <div style="margin-bottom: 5px; font-weight: bold; color: #333;">
            ▶ ILOT : <span style="border-bottom: 1px dotted #000;">{{ $souscription->attributionLot->ilot ?? '.......' }}</span>
            <span style="margin-left: 20px;">LOT : </span><span style="border-bottom: 1px dotted #000;">{{ $souscription->attributionLot->lot ?? '.......' }}</span>
        </div>
        <div style="margin-bottom: 10px; font-weight: bold; color: #333;">
            ▶ Surface totale : <span style="border-bottom: 1px dotted #000;">{{ $souscription->attributionLot->superficie ?? '.......' }}</span> m2
            <span style="margin-left: 20px;">Surface bâtie : </span><span style="border-bottom: 1px dotted #000;">{{ optional($souscription->bienImmobilier)->surface_habitable ?? '....................' }}</span> m2
        </div>

        @php
            $b = $souscription->bienImmobilier;
            $amenities = [];
            if($b){
                if($b->garage) $amenities[] = 'Un garage';
                if($b->cour_avant) $amenities[] = 'Une cour avant';
                if($b->cour_arriere) $amenities[] = 'Une cour arrière';
                if($b->terrasse_carrelee) $amenities[] = 'Une terrasse entièrement carrelée';
                if($b->grand_sejour_carrele) $amenities[] = 'Un grand séjour entièrement carrelé';
                if($b->chambre_principale_carrelee) $amenities[] = 'Une chambre principale carrelée';
                if($b->chambre_principale_salle_eau) $amenities[] = "Salle d'eau dans la chambre principale";
                if($b->chambre_principale_placards) $amenities[] = 'Deux placards dans la chambre principale';
                if($b->chambre_2_carrelee) $amenities[] = 'Une chambre enfants carrelée';
                if($b->chambre_2_salle_eau) $amenities[] = "Salle d'eau dans la chambre enfants";
                if($b->chambre_2_placard) $amenities[] = 'Un placard dans la chambre enfants';
                if($b->wc_visiteur_carrele) $amenities[] = 'Un WC visiteur carrelé';
                if($b->grande_cuisine_carrelee) $amenities[] = 'Une grande cuisine carrelée';
                if($b->buanderie) $amenities[] = 'Une buanderie (débarras)';
                if($b->installation_chauffe_eau) $amenities[] = 'Installation pour chauffe-eau';
                if($b->piscine) $amenities[] = 'Une piscine';
                if($b->terasse) $amenities[] = 'Une terrasse';
                if($b->jardin) $amenities[] = 'Un jardin';
                if($b->dependance) $amenities[] = 'Une dépendance';
            }
        @endphp
        @if(!empty($amenities))
            <ul class="amenities-list">
                @foreach($amenities as $a)
                    <li>{{ $a }}</li>
                @endforeach
            </ul>
        @endif

        <div class="land-title-info">
            A détacher par voie de morcellement du Titre Foncier n°203788 de la Circonscription
        </div>
        
        <div style="margin-bottom: 10px; font-size: 10px;">
            Foncière d'ANYAMAN et après le paiement intégral du prix du logement :
        </div>

        <table class="financial-table">
            <tr>
                <td style="width: 200px; font-weight:bold;">PRIX DE VENTE DU LOGEMENT :</td>
                <td style="border-bottom: 1px dotted #000;">{{ number_format($souscription->prix_logement, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">ACOMPTE PAYE :</td>
                <td style="border-bottom: 1px dotted #000;">
                    @php
                        $totalPaye = $souscription->paiements()->where('statut', 'payé')->sum('montant');
                    @endphp
                    {{ number_format($totalPaye, 0, ',', ' ') }} FCFA
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">RESTE À PAYER :</td>
                <td style="border-bottom: 1px dotted #000;">
                    {{ number_format(max($souscription->prix_logement - $totalPaye, 0), 0, ',', ' ') }} FCFA
                </td>
            </tr>
        </table>

        <div class="closing-text">
            En foi de quoi, la présente attestation de réservation lui est délivrée pour servir et valoir ce que de droit.
        </div>

        <table class="signatures-table">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <div style="color: #004A80; font-weight: bold; margin-bottom: 5px;">Visa du client</div>
                    <div style="font-size: 8px; font-style: italic;">(Précédé de la mention « Lu et approuvé »)</div>
                </td>
                <td style="width: 50%; text-align: right; vertical-align: top;">
                    <div style="margin-bottom: 5px;">Fait à ABIDJAN le {{ now()->format('d/m/Y') }}</div>
                    <div style="font-size: 9px; margin-bottom: 5px;">(En deux copies originales)</div>
                    <div style="color: #004A80; font-weight: bold;">Le Directeur Général</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Siège Social : Abidjan Cocody - 2 Plateaux Macaci non loin de la Pharmacie ORCHID - Cité Sicogi Villa 280 Lot 53 - ilot 19<br>
        28 B.P .70 ABIDJAN 28 - Tel : +225 21 20 80 54 20 / 07 03 94 03 14 - Whatsapp : +225 07 03 94 03 14 / 07 12 42 42 42<br>
        RCCM : CI-ABJ-03-2023-M-11022 - CC N° : 1517590M - E-mail- : emmanuela.kore@jare-indudtries.com
    </div>
</body>
</html>

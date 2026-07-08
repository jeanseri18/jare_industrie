<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche de souscription</title>
    <style>
        @page { size: A4 portrait; margin: 0; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; color: #1f2a44; }
        .page { padding: 10px 12px 62px; position: relative; z-index: 2; }
        .watermark { position: fixed; left: 0; right: 0; top: 0; bottom: 0; z-index: 1; text-align: center; }
        .watermark img { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); width: 96%; max-width: 96%; opacity: 0.10; }
        .top-table { width: 100%; border-collapse: collapse; }
        .top-table td { vertical-align: top; }
        .logo { width: 18%; }
        .title { width: 64%; text-align: center; padding-top: 4px; }
        .logo-right { width: 18%; text-align: right; }
        .company-name { font-size: 22px; font-weight: 900; color: #ff7200; text-transform: uppercase; margin: 0; letter-spacing: 0.5px; }
        .company-sub { font-size: 15px; font-weight: 900; color: #1e3a8a; text-transform: uppercase; margin: 3px 0 0; letter-spacing: 3px; }
        .header-block { width: 90%; margin: 0 auto; }
        .bar-table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .bar-left { background: #ff7200; color: #fff; font-weight: 900; font-size: 16px; padding: 6px 10px; text-transform: uppercase; }
        .bar-right { border: 2px solid #ff7200; font-weight: 900; font-size: 12px; padding: 6px 8px; }
        .dots { display: inline-block; border-bottom: 2px dotted #1e3a8a; min-width: 160px; height: 14px; vertical-align: baseline; }
        .dots .dots-text { display: inline-block; padding-left: 6px; font-weight: 900; color: #1f2a44; }
        .site-bar { background: #1e3a8a; color: #fff200; font-weight: 900; padding: 4px 8px; margin-top: 5px; font-size: 11px; }
        .section-head { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .section-head td { vertical-align: middle; }
        .section-title { color: #ff7200; font-weight: 900; font-size: 13px; text-transform: uppercase; }
        .dossier-wrap { text-align: right; }
        .dossier-label { color: #ff7200; font-weight: 900; font-size: 13px; }
        .dossier-box { display: inline-block; border: 2px solid #ff7200; width: 170px; height: 18px; line-height: 18px; vertical-align: middle; margin-left: 8px; padding: 0 6px; box-sizing: border-box; font-weight: 900; color: #1f2a44; text-align: left; overflow: hidden; white-space: nowrap; }
        .form-table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .form-table td { padding: 4px 3px; vertical-align: middle; font-size: 11px; }
        .left-label { width: 32%; font-weight: 900; text-transform: uppercase; color: #1e3a8a; }
        .line { width: 68%; border-bottom: 2px dotted #1e3a8a; height: 16px; }
        .line span { display: inline-block; padding-left: 6px; font-weight: 700; color: #1f2a44; }
        .boxes-row { width: 66%; }
        .box-label { font-weight: 900; color: #1f2a44; margin-right: 6px; }
        .cb { display: inline-block; width: 14px; height: 10px; border: 1px solid #1f2a44; margin: 0 8px 0 5px; vertical-align: middle; }
        .cb.on { background: #1f2a44; }
        .contract-block { margin-top: 10px; }
        .housing-title { text-align: center; color: #ff7200; font-weight: 900; text-transform: uppercase; margin: 6px 0 4px; font-size: 12px; }
        .housing-table { width: 100%; border-collapse: collapse; }
        .housing-table td { width: 33.3333%; text-align: left; vertical-align: top; padding: 2px 4px; }
        .housing-card-title { font-size: 10px; font-weight: 900; color: #1e3a8a; text-transform: uppercase; margin-bottom: 2px; }
        .housing-check { margin-top: 2px; text-align: right; }
        .value-row { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .value-row td { padding: 4px 3px; font-size: 11px; }
        .value-label { font-weight: 900; color: #1e3a8a; }
        .value-box { border: 1px solid #1f2a44; height: 16px; }
        .value-box span { padding-left: 6px; display: inline-block; font-weight: 700; }
        .small-box { border: 1px solid #1f2a44; width: 24px; height: 16px; display: inline-block; vertical-align: middle; margin-left: 6px; }
        .fees-row { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .fees-row td { padding: 6px 3px; }
        .fees-label { color: #ff7200; font-weight: 900; text-transform: uppercase; }
        .fees-amount { background: #ff7200; color: #fff; font-weight: 900; font-size: 18px; padding: 3px 8px; display: inline-block; }
        .fees-note { color: #ff7200; font-weight: 900; margin-left: 8px; text-transform: uppercase; }
        .fees-sub { font-size: 11px; font-weight: 700; color: #1f2a44; }
        .signatures { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .signatures td { width: 50%; vertical-align: top; font-size: 11px; font-weight: 900; color: #1e3a8a; text-transform: uppercase; }
        .signatures .right { text-align: right; }
        .sign-line { border-bottom: 1px solid #1f2a44; margin-top: 12px; }
      /*  .bottom-red { position: fixed; left: 0; right: 0; bottom: 54px; height: 2px; background: #ff7200; z-index: 20; }*/
        .footer { position: fixed; left: 0; right: 0; bottom: 0; height: 54px; background: #1e3a8a; color: #fff; padding: 8px 12px; font-size: 9px; line-height: 1.2; text-align: center; z-index: 10; border-top: 4px solid #ff7200; }
    </style>
    @include('documents.partials._brand_styles')
</head>
<body>
    @include('documents.partials._watermark')
    @php
        $cl = $souscription->client;
        $clientNom = strtoupper($cl->nom ?? $souscription->nom ?? '');
        $clientPrenom = strtoupper($cl->prenom ?? $souscription->prenom ?? '');
        $clientNomPrenom = trim($clientNom . ' ' . $clientPrenom);
        $dateNaissance = ($cl->date_naissance ?? $souscription->date_naissance) ? ($cl->date_naissance ?? $souscription->date_naissance)->format('d/m/Y') : '';
        $lieuNaissance = $cl->lieu_naissance ?? $souscription->lieu_naissance ?? '';
        $nationalite = strtoupper($cl->nationalite ?? $souscription->nationalite ?? '');
        $profession = strtoupper($cl->profession ?? $souscription->profession ?? '');
        $telephone = $cl->telephone ?? $souscription->telephone ?? '';
        $email = $cl->email ?? $souscription->email ?? '';
        $entreprise = strtoupper($cl->entreprise ?? $souscription->entreprise ?? '');
        $salaire = (int)($cl->salaire_mensuel ?? $souscription->salaire_mensuel ?? 0);
        $naturePiece = strtoupper($cl->nature_piece ?? $souscription->nature_piece ?? '');
        $numeroPiece = $cl->numero_piece ?? $souscription->numero_piece ?? '';
        $residence = strtoupper($cl->lieu_residence ?? $souscription->lieu_residence ?? '');
        $villeFiche = strtoupper($cl->ville ?? $souscription->ville ?? '');
        $paysFiche = strtoupper($cl->pays ?? $souscription->pays ?? '');
        $ddPiece = $cl->date_delivrance_piece ?? $souscription->date_delivrance_piece ?? null;
        $dePiece = $cl->date_expiration_piece ?? $souscription->date_expiration_piece ?? null;
        $dateDelivranceFiche = $ddPiece ? $ddPiece->format('d/m/Y') : '';
        $dateExpirationFiche = $dePiece ? $dePiece->format('d/m/Y') : '';
        $sitMat = strtolower((string)($cl->situation_matrimoniale ?? $souscription->situation_matrimoniale ?? ''));
        $nbEnfants = (int)($cl->nombre_enfants ?? $souscription->nombre_enfants ?? 0);
        $ayantDroit = is_array($cl->ayant_droit ?? null) ? implode(', ', ($cl->ayant_droit ?? [])) : ($cl->ayant_droit ?? $souscription->ayant_droit ?? '');
        $typeLogement = strtoupper((string)($souscription->type_logement ?? ($souscription->bienImmobilier->titre ?? '')));
        $valeurSouscription = (int)($souscription->valeur_souscription ?? $souscription->prix_logement ?? 0);
        $modePaiement = strtoupper((string)($souscription->mode_paiement ?? ''));
        $apport = (int)($souscription->apport_initial ?? 0);
        $dureeMois = (int)($souscription->duree_contrat_mois ?? 0);
        $fraisSouscription = (int)($souscription->frais_souscription ?? 500000);
        $siteLine = "Cité Alain Richard DONWAHII D'Ebimpé, Route d'Anyama non loin du stade Olympique";
        $dossierRef = $souscription->ref_souscription ?? ('SOUS-' . $souscription->id);
        $dateFiche = $souscription->created_at ? $souscription->created_at->format('d/m/Y') : now()->format('d/m/Y');
    @endphp

    <div class="page">
        @include('documents.partials._pdf_header')

        <div class="header-block">
            <table class="bar-table">
                <tr>
                    <td class="bar-left">FICHE DE SOUSCRIPTION</td>
                    <td class="bar-right">DATE : <span class="dots"><span class="dots-text">{{ $dateFiche }}</span></span></td>
                </tr>
            </table>
            <div class="site-bar">{{ $siteLine }}</div>

            <table class="section-head">
                <tr>
                    <td class="section-title">IDENTIFICATION DU CLIENT</td>
                    <td class="dossier-wrap">
                        <span class="dossier-label">DOSSIER N°</span>
                        <span class="dossier-box">{{ $dossierRef }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <table class="form-table">
            <tr>
                <td class="left-label">NOM</td>
                <td class="line"><span>{{ $clientNom }}</span></td>
            </tr>
            <tr>
                <td class="left-label">PRÉNOMS</td>
                <td class="line"><span>{{ $clientPrenom }}</span></td>
            </tr>
            <tr>
                <td class="left-label">DATE ET LIEU DE NAISSANCE</td>
                <td class="line"><span>{{ trim($dateNaissance . ' ' . $lieuNaissance) }}</span></td>
            </tr>
            <tr>
                <td class="left-label">NATIONALITÉ</td>
                <td class="line"><span>{{ $nationalite }}</span></td>
            </tr>
            <tr>
                <td class="left-label">PROFESSION</td>
                <td class="line"><span>{{ $profession }}</span></td>
            </tr>
            <tr>
                <td class="left-label">NUMÉRO DE TÉLÉPHONE</td>
                <td class="line"><span>{{ $telephone }}</span></td>
            </tr>
            <tr>
                <td class="left-label">E-MAIL</td>
                <td class="line"><span>{{ $email }}</span></td>
            </tr>
            <tr>
                <td class="left-label">NOM DE L'ENTREPRISE</td>
                <td class="line"><span>{{ $entreprise }}</span></td>
            </tr>
            <tr>
                <td class="left-label">SALAIRE MENSUEL</td>
                <td class="line"><span>{{ $salaire ? number_format($salaire, 0, ',', ' ') : '' }}</span></td>
            </tr>
            <tr>
                <td class="left-label">NATURE DE LA PIÈCE</td>
                <td class="boxes-row">
                    <span class="box-label">CNI</span><span class="cb {{ $naturePiece === 'CNI' ? 'on' : '' }}"></span>
                    <span class="box-label">ATTEST.</span><span class="cb {{ $naturePiece === 'ATTEST' ? 'on' : '' }}"></span>
                    <span class="box-label">PASSEPORT</span><span class="cb {{ $naturePiece === 'PASSEPORT' ? 'on' : '' }}"></span>
                    <span class="box-label">CC</span><span class="cb {{ $naturePiece === 'CARTE CONSULAIRE' ? 'on' : '' }}"></span>
                </td>
            </tr>
            <tr>
                <td class="left-label">NUMÉRO DE LA PIÈCE</td>
                <td class="line"><span>{{ $numeroPiece }}</span></td>
            </tr>
            <tr>
                <td class="left-label">DATE DE DÉLIVRANCE DE LA PIÈCE</td>
                <td class="line"><span>{{ $dateDelivranceFiche }}</span></td>
            </tr>
            <tr>
                <td class="left-label">DATE D'EXPIRATION DE LA PIÈCE</td>
                <td class="line"><span>{{ $dateExpirationFiche }}</span></td>
            </tr>
            <tr>
                <td class="left-label">LIEU DE RÉSIDENCE ACTUEL</td>
                <td class="line"><span>{{ $residence }}</span></td>
            </tr>
            <tr>
                <td class="left-label">VILLE</td>
                <td class="line"><span>{{ $villeFiche }}</span></td>
            </tr>
            <tr>
                <td class="left-label">PAYS</td>
                <td class="line"><span>{{ $paysFiche }}</span></td>
            </tr>
            <tr>
                <td class="left-label">SITUATION MATRIMONIALE</td>
                <td class="boxes-row">
                    <span class="box-label">Marié(e)</span><span class="cb {{ $sitMat === 'marie' ? 'on' : '' }}"></span>
                    <span class="box-label">Divorcé(e)</span><span class="cb {{ $sitMat === 'divorce' ? 'on' : '' }}"></span>
                    <span class="box-label">Célibataire</span><span class="cb {{ $sitMat === 'celibataire' ? 'on' : '' }}"></span>
                    <span class="box-label">Veuf(ve)</span><span class="cb {{ $sitMat === 'veuf' ? 'on' : '' }}"></span>
                </td>
            </tr>
            <tr>
                <td class="left-label">NOMBRE D'ENFANTS</td>
                <td class="line"><span>{{ $nbEnfants ?: '' }}</span></td>
            </tr>
            <tr>
                <td class="left-label">NOM AYANT DROIT</td>
                <td class="line"><span>{{ $ayantDroit }}</span></td>
            </tr>
            <tr>
                <td class="left-label">SUPERFICIE DU TERRAIN</td>
                <td class="line"><span>{{ $souscription->attributionLot->superficie ?? '' }}</span></td>
            </tr>
            <tr>
                <td class="left-label">DOCUMENTS ADMINISTRATIFS DE TERRAIN</td>
                <td class="line"><span></span></td>
            </tr>
        </table>

        <div class="contract-block">
            <div class="housing-title">TYPE DE LOGEMENT</div>
            <table class="housing-table">
                @php
                    $biensProjet = optional($souscription->projet)->bien_immobiliers ?? collect();
                    $selectedBienId = (int) ($souscription->bien_immobilier_id ?? 0);
                @endphp
                @forelse($biensProjet->chunk(3) as $row)
                    <tr>
                        @foreach($row as $bien)
                            @php
                                $isSelected = $selectedBienId && ((int) $bien->id === $selectedBienId);
                                if (!$isSelected) {
                                    $isSelected = strtoupper((string) $bien->titre) === $typeLogement;
                                }
                            @endphp
                            <td>
                                <div class="housing-card-title">{{ $bien->titre }}</div>
                                <div class="housing-check"><span class="cb {{ $isSelected ? 'on' : '' }}"></span></div>
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="housing-card-title">—</div>
                        </td>
                    </tr>
                @endforelse
            </table>

            <table class="value-row">
                <tr>
                    <td style="width: 22%;" class="value-label">Valeur de la souscription</td>
                    <td style="width: 38%;" class="value-box"><span>{{ $valeurSouscription ? number_format($valeurSouscription, 0, ',', ' ') : '' }}</span></td>
                    <td style="width: 20%;" class="value-label">Location vente</td>
                    <td style="width: 20%;"><span class="small-box"></span> <span class="small-box"></span></td>
                </tr>
                <tr>
                    <td class="value-label">Mode de Paiement :</td>
                    <td colspan="3">
                        <span class="box-label">Crédit Bancaire</span><span class="cb {{ $modePaiement === 'CREDIT_BANCAIRE' ? 'on' : '' }}"></span>
                        <span class="box-label">Tempérament</span><span class="cb {{ $modePaiement === 'TEMPERAMENT' ? 'on' : '' }}"></span>
                        <span class="box-label">Virement</span><span class="cb {{ $modePaiement === 'VIREMENT' ? 'on' : '' }}"></span>
                        <span class="box-label">Prélèvement à la source</span><span class="cb {{ $modePaiement === 'PRELEVEMENT_SOURCE' ? 'on' : '' }}"></span>
                    </td>
                </tr>
                <tr>
                    <td class="value-label">Apport initial (%)</td>
                    <td class="value-box"><span>{{ $apport ? number_format($apport, 0, ',', ' ') : '' }}</span></td>
                    <td class="value-label">Solde à payer</td>
                    <td class="value-box"><span></span></td>
                </tr>
                <tr>
                    <td class="value-label"><span style="text-decoration: underline;">DURÉE DU CONTRAT :</span></td>
                    <td colspan="3">
                        <span class="box-label">En année</span> <span class="small-box"></span>
                        <span class="box-label" style="margin-left:12px;">En Mois</span> <span class="small-box"><span style="padding-left:6px; font-weight:700;">{{ $dureeMois ?: '' }}</span></span>
                        <span class="box-label" style="margin-left:12px;">Autre</span> <span class="small-box"></span>
                    </td>
                </tr>
            </table>

            <table class="fees-row">
                <tr>
                    <td style="width: 34%;">
                        <span class="fees-label">FRAIS DE SOUSCRIPTION :</span>
                    </td>
                    <td style="width: 30%;">
                        <span class="fees-amount">{{ number_format($fraisSouscription ?: 500000, 0, ',', ' ') }} FCFA</span>
                    </td>
                    <td style="width: 36%; text-align:left;">
                        <span class="fees-note">NON REMBOURSABLE</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="fees-sub">
                        Mise à disposition : Plan de maison, Plan de masse, Cahier de charges des travaux de construction
                    </td>
                </tr>
            </table>

            <table class="signatures">
                <tr>
                    <td>
                        SIGNATURE DU CLIENT<br>
                        <span style="font-weight:700; text-transform:none;">(Mention Lu et Approuvé)</span>
                    </td>
                    @include('documents.partials._signature')
                </tr>
            </table>
        </div>
    </div>

    <div class="bottom-red"></div>
    @include('documents.partials._pdf_footer')
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche de souscription</title>
    <style>
        @page { margin: 0.6cm 1cm; }
        body { font-family: Arial, sans-serif; font-size: 11px; line-height: 1.35; color: #000; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        .logo-cell { width: 20%; vertical-align: top; }
        .title-cell { width: 60%; text-align: center; vertical-align: top; padding-top: 10px; }
        .right-cell { width: 20%; text-align: right; vertical-align: top; }
        .company-name { font-size: 22px; font-weight: 900; color: #E30613; text-transform: uppercase; margin: 0; }
        .company-sub { font-size: 14px; font-weight: 700; color: #004A80; text-transform: uppercase; margin: 0; }
        .doc-title-box { background-color: #E30613; color: #fff; font-weight: bold; font-size: 16px; text-align: center; padding: 6px 10px; border-radius: 10px; margin: 10px auto 8px; width: 80%; text-transform: uppercase; border: 2px solid #004A80; }
        .ref-number { text-align: center; font-size: 13px; font-weight: bold; color: #004A80; margin-bottom: 8px; }
        .section-title { text-align: center; font-weight: bold; color: #004A80; text-transform: uppercase; margin: 10px 0 6px; font-size: 12px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .info-table td { padding: 4px 6px; vertical-align: top; }
        .label { font-weight: bold; }
        .box { border: 1px solid #cfe2ff; border-left: 4px solid #004A80; border-radius: 8px; padding: 10px; margin-bottom: 8px; background: #f8fbff; }
        .two-col { width: 100%; border-collapse: collapse; }
        .two-col td { width: 50%; vertical-align: top; padding: 0 6px 0 0; }
        .money-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .money-table td { padding: 4px 6px; border-bottom: 1px solid #eee; }
        .money-table td:last-child { text-align: right; font-weight: bold; }
        .sign-table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        .sign-table td { width: 50%; vertical-align: top; text-align: center; padding-top: 6px; }
        .sign-line { border-bottom: 1px dotted #000; width: 80%; margin: 45px auto 6px; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; background-color: #004A80; color: #fff; text-align: center; font-size: 8px; padding: 5px; line-height: 1.2; }
        .page-content { padding-bottom: 38px; }
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
                        <div style="color:#004A80; font-weight:bold;">JARE INDUSTRIES</div>
                    @endif
                </td>
                <td class="title-cell">
                    <div class="company-name">JARE INDUSTRIES</div>
                    <div class="company-sub">PROMOTEUR IMMOBILIER AGRÉÉ</div>
                    <div style="font-size: 10px; font-weight: 700; color: #004A80; margin-top: 4px;">
                        N° d’agrément du promoteur : {{ $souscription->projet->numero_agrement ?? '—' }}
                    </div>
                </td>
                <td class="right-cell">
                    <div style="font-size: 10px; color: #004A80; font-weight: 700;">
                        Date : {{ now()->format('d/m/Y') }}
                    </div>
                </td>
            </tr>
        </table>

        <div class="doc-title-box">FICHE DE SOUSCRIPTION</div>
        <div class="ref-number">Référence : {{ $souscription->ref_souscription ?? ('SOUS-' . $souscription->id) }}</div>

        <div class="box">
            <table class="info-table">
                <tr>
                    <td><span class="label">Catégorie client :</span> {{ strtoupper($souscription->categorie_client ?? '—') }}</td>
                    <td>
                        <span class="label">Mutuelle :</span>
                        @if(($souscription->client->mutuelle->nom ?? null))
                            {{ $souscription->client->mutuelle->nom }}
                        @else
                            —
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="section-title">Identité du client</div>
        <table class="info-table">
            <tr>
                <td><span class="label">Nom & Prénoms :</span> {{ strtoupper(($souscription->client->nom ?? $souscription->nom ?? '') . ' ' . ($souscription->client->prenom ?? $souscription->prenom ?? '')) }}</td>
                <td><span class="label">Téléphone :</span> {{ $souscription->client->telephone ?? $souscription->telephone ?? '—' }}</td>
            </tr>
            <tr>
                <td><span class="label">Email :</span> {{ $souscription->client->email ?? $souscription->email ?? '—' }}</td>
                <td><span class="label">Nationalité :</span> {{ $souscription->client->nationalite ?? $souscription->nationalite ?? '—' }}</td>
            </tr>
            <tr>
                <td>
                    <span class="label">Né(e) le :</span>
                    {{ ($souscription->client->date_naissance ?? $souscription->date_naissance) ? ($souscription->client->date_naissance ?? $souscription->date_naissance)->format('d/m/Y') : '—' }}
                    <span class="label" style="margin-left:10px;">à</span>
                    {{ $souscription->client->lieu_naissance ?? $souscription->lieu_naissance ?? '—' }}
                </td>
                <td><span class="label">Nombre d’enfants :</span> {{ $souscription->client->nombre_enfants ?? $souscription->nombre_enfants ?? 0 }}</td>
            </tr>
            <tr>
                <td><span class="label">Pièce :</span> {{ strtoupper($souscription->client->nature_piece ?? $souscription->nature_piece ?? '—') }}</td>
                <td><span class="label">N° pièce :</span> {{ $souscription->client->numero_piece ?? $souscription->numero_piece ?? '—' }}</td>
            </tr>
            <tr>
                <td colspan="2"><span class="label">Ayant droit :</span> {{ is_array($souscription->client->ayant_droit ?? null) ? implode(', ', ($souscription->client->ayant_droit ?? [])) : ($souscription->client->ayant_droit ?? $souscription->ayant_droit ?? '—') }}</td>
            </tr>
        </table>

        <div class="section-title">Programme & Logement</div>
        <table class="info-table">
            <tr>
                <td><span class="label">Programme :</span> {{ $souscription->projet->nom ?? $souscription->programme ?? '—' }}</td>
                <td><span class="label">Type logement :</span> {{ $souscription->type_logement ?? ($souscription->bienImmobilier->titre ?? '—') }}</td>
            </tr>
            <tr>
                <td><span class="label">Date début contrat :</span> {{ $souscription->date_debut ? $souscription->date_debut->format('d/m/Y') : '—' }}</td>
                <td><span class="label">Date fin contrat :</span> {{ $souscription->date_fin ? $souscription->date_fin->format('d/m/Y') : '—' }}</td>
            </tr>
            <tr>
                <td><span class="label">Mode de paiement :</span> {{ strtoupper(str_replace('_', ' ', $souscription->mode_paiement ?? '—')) }}</td>
                <td><span class="label">Salaire mensuel :</span> {{ number_format((int)($souscription->client->salaire_mensuel ?? $souscription->salaire_mensuel ?? 0), 0, ',', ' ') }} FCFA</td>
            </tr>
        </table>

        <div class="section-title">Résumé financier</div>
        <div class="box">
            <table class="money-table">
                <tr>
                    <td>Valeur de la souscription</td>
                    <td>{{ number_format((int)($souscription->valeur_souscription ?? $souscription->prix_logement ?? 0), 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <td>Apport initial</td>
                    <td>{{ number_format((int)($souscription->apport_initial ?? 0), 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <td>Frais de souscription (non remboursables)</td>
                    <td>{{ number_format((int)($souscription->frais_souscription ?? 0), 0, ',', ' ') }} FCFA</td>
                </tr>
            </table>
        </div>

        <div class="section-title">Signatures</div>
        <table class="sign-table">
            <tr>
                <td>
                    <div>Client</div>
                    <div class="sign-line"></div>
                    <div>Nom & Prénoms</div>
                </td>
                <td>
                    <div>JARE INDUSTRIES</div>
                    <div class="sign-line"></div>
                    <div>Cachet & Signature</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        JARE INDUSTRIES — Promoteur Immobilier Agréé • Abidjan Cocody, Les Deux Plateaux • Contacts : +225 … / … • Email : …<br>
        Document généré automatiquement depuis le système de souscription.
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lettre d’Attribution Définitive</title>
    <style>
        @page {
            margin: 0.5cm 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #000;
        }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        .logo-cell { width: 20%; vertical-align: top; }
        .title-cell { width: 60%; text-align: center; vertical-align: top; padding-top: 10px; }
        .qr-cell { width: 20%; text-align: right; vertical-align: top; }
        .company-name { font-size: 22px; font-weight: 900; color: #E30613; text-transform: uppercase; margin: 0; }
        .company-sub { font-size: 14px; font-weight: 700; color: #004A80; text-transform: uppercase; margin: 0; }
        .doc-title-box {
            background-color: #E30613; color: white; font-weight: bold; font-size: 16px;
            text-align: center; padding: 5px 10px; border-radius: 10px; margin: 10px auto;
            width: 80%; text-transform: uppercase; border: 2px solid #004A80;
        }
        .ref-number { text-align: center; font-size: 14px; font-weight: bold; color: #004A80; margin-bottom: 10px; }
        .intro-text { text-align: justify; margin-bottom: 10px; font-size: 10px; }
        .section-title { text-align: center; font-weight: bold; color: #004A80; margin: 10px 0; font-size: 12px; text-transform: uppercase; }
        .client-info-table { width: 100%; margin-bottom: 10px; font-size: 11px; }
        .client-info-table td { padding: 3px 0; }
        .label { font-weight: bold; }
        .amenities-list { list-style-type: none; padding-left: 0; margin: 6px 0 10px; }
        .amenities-list li { margin: 3px 0; }
        .footer-line { margin-top: 10px; font-weight: bold; color: #333; }
        .sign { margin-top: 24px; display: flex; justify-content: space-between; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('LOGO.png') }}" alt="Logo" style="max-width: 120px;">
            </td>
            <td class="title-cell">
                <div class="company-name">JARE INDUSTRIES</div>
                <div class="company-sub">Immobilier • Construction</div>
                <div style="font-size: 10px; font-weight: 700; color: #004A80; margin-top: 4px;">
                    N° d’agrément du promoteur : {{ $souscription->projet->numero_agrement ?? '—' }}
                </div>
            </td>
            <td class="qr-cell"></td>
        </tr>
    </table>

    <div class="doc-title-box">LETTRE D’ATTRIBUTION DÉFINITIVE</div>
    <div class="ref-number">Référence: {{ $souscription->ref_souscription }}</div>

    <table class="client-info-table">
        <tr>
            <td class="label" style="width: 220px;">Société / Structure</td>
            <td>JARE INDUSTRIES</td>
        </tr>
        <tr>
            <td class="label">Adresse</td>
            <td>Abidjan Cocody – 2 Plateaux Macaci, Villa 150, Lot 53</td>
        </tr>
        <tr>
            <td class="label">Téléphone / Email</td>
            <td>+225 21 20 80 54 20 • emmanuela.kore@jare-indudtries.com</td>
        </tr>
    </table>

    <table class="client-info-table" style="margin-top:8px;">
        <tr>
            <td class="label" style="width: 220px;">À</td>
            <td>{{ ($validation->nom_client . ' ' . ($validation->prenom_client ?? '')) ?: ($souscription->client->nom_prenom ?? '') }}</td>
        </tr>
        <tr>
            <td class="label">Adresse</td>
            <td>{{ $validation->lieu_residence ?? '...' }}</td>
        </tr>
        <tr>
            <td class="label">Téléphone</td>
            <td>{{ $validation->numero_telephone ?? '...' }}</td>
        </tr>
    </table>

    <div class="intro-text">
        Objet : Attribution définitive d’un bien immobilier
    </div>
    <div class="intro-text">
        Monsieur / Madame,<br>
        Par la présente, nous avons l’honneur de vous notifier l’attribution définitive du bien immobilier ci-dessous désigné, dans le cadre du programme immobilier {{ $souscription->projet->nom ?? '...' }}.
    </div>

    <div class="section-title">Désignation du bien</div>
    <table class="client-info-table">
        <tr>
            <td class="label" style="width: 220px;">Type de bien</td>
            <td>{{ $souscription->type_logement ?? '...' }}</td>
        </tr>
        <tr>
            <td class="label">Lot / Parcelle</td>
            <td>{{ $validation->lot ?? ($souscription->attributionLot->lot ?? '...') }}</td>
        </tr>
        <tr>
            <td class="label">Ilot</td>
            <td>{{ $validation->ilot_attribue ?? ($souscription->attributionLot->ilot ?? '...') }}</td>
        </tr>
        <tr>
            <td class="label">Superficie</td>
            <td>{{ $souscription->attributionLot->superficie ?? '...' }} m²</td>
        </tr>
        <tr>
            <td class="label">Localisation</td>
            <td>{{ $souscription->projet->localisation ?? '...' }}</td>
        </tr>
        <tr>
            <td class="label">Référence du plan de lotissement</td>
            <td>...</td>
        </tr>
    </table>

    <div class="intro-text">
        Cette attribution intervient après règlement intégral du prix du bien, conformément aux dispositions du contrat de réservation / contrat de vente signé entre les parties.
    </div>
    <div class="intro-text">
        En conséquence, le bien susmentionné vous est définitivement attribué, sous réserve des formalités administratives relatives à l’établissement des documents de propriété (ACD, titre foncier ou tout autre document légal applicable).
    </div>
    <div class="intro-text">
        La présente lettre tient lieu de preuve d’attribution définitive, dans l’attente de la délivrance des titres de propriété définitifs par les autorités compétentes.
    </div>
    <div class="intro-text">
        Veuillez agréer, Monsieur / Madame, l’expression de nos salutations distinguées.
    </div>

    <div style="margin-top: 10px;">Fait à ABIDJAN, le {{ now()->format('d/m/Y') }}</div>
    <div class="sign">
        <div>
            <div class="label">Pour la société / le promoteur</div>
            <div>Nom : Roland KOUAKOU</div>
            <div>Fonction : Directeur Général</div>
        </div>
        <div>Signature et cachet</div>
    </div>
</body>
</html>

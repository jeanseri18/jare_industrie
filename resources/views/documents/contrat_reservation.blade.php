@php
    $client = $souscription->client;
    $projet = $souscription->projet;
    $bien = $souscription->bienImmobilier;
    $attribution = $souscription->attributionLot;
    $validation = $souscription->validationSouscription;

    $qrSvg = null;
    try {
        $qrUrl = \Illuminate\Support\Facades\URL::signedRoute('public.souscriptions.contrat-reservation', ['souscription' => $souscription->id]);
        $qrSvg = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(95)->margin(0)->generate($qrUrl));
    } catch (\Throwable $e) {
        $qrSvg = null;
    }

    $acquereurNom = strtoupper(trim(($client->nom ?? $souscription->nom ?? '') . ' ' . ($client->prenom ?? $client->prenoms ?? $souscription->prenom ?? '')));
    $fonction = strtoupper($client->profession ?? $souscription->profession ?? '');
    $adresse = strtoupper($client->lieu_residence ?? $souscription->lieu_residence ?? $client->adresse ?? '');
    $boitePostale = $client->boite_postale ?? $client->bp ?? '';
    $ville = strtoupper($client->ville ?? $souscription->ville ?? '');
    $pays = strtoupper($client->pays ?? $souscription->pays ?? $client->nationalite ?? $souscription->nationalite ?? '');
    $nationalite = strtoupper($client->nationalite ?? $souscription->nationalite ?? '');
    $dateNaissance = ($client->date_naissance ?? $souscription->date_naissance) ? ($client->date_naissance ?? $souscription->date_naissance)->format('d/m/Y') : '';
    $lieuNaissance = strtoupper($client->lieu_naissance ?? $souscription->lieu_naissance ?? '');
    $numeroPiece = $client->numero_piece ?? $souscription->numero_piece ?? '';
    $ddP = $client->date_delivrance_piece ?? $souscription->date_delivrance_piece ?? null;
    $deP = $client->date_expiration_piece ?? $souscription->date_expiration_piece ?? null;
    $dateDelivrancePiece = $ddP ? $ddP->format('d/m/Y') : '';
    $dateExpirationPiece = $deP ? $deP->format('d/m/Y') : '';
    $situationMatrimoniale = strtoupper($client->situation_matrimoniale ?? $souscription->situation_matrimoniale ?? '');

    $projetNom = strtoupper($projet->nom ?? '');
    $zoneProjet = strtoupper($projet->localisation ?? 'AKOUPE ZEUDJI');
    $titreFoncier = $projet->titre_foncier ?? null;
    $circonscriptionFoncier = $projet->circonscription_fonciere ?? $projet->localisation ?? null;
    $bienNom = strtoupper($bien->titre ?? $bien->nom ?? $souscription->type_logement ?? '');
    $superficieTerrain = $attribution->superficie ?? $validation->superficie ?? '';
    $surfaceUtile = $bien->surface_habitable ?? $bien->surface_utile ?? '';

    $prixVente = (int)($souscription->prix_logement ?? $souscription->valeur_souscription ?? 0);
    $prixVenteFormate = $prixVente ? number_format($prixVente, 0, ',', ' ') : '';
    $prixVenteLettres = '';
    if ($prixVente > 0 && extension_loaded('intl')) {
        try {
            $fmt = new \NumberFormatter('fr_FR', \NumberFormatter::SPELL_OUT);
            $fmt->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 0);
            $prixVenteLettres = mb_strtoupper((string) $fmt->format($prixVente));
        } catch (\Throwable $e) {
            $prixVenteLettres = '';
        }
    }
    $fraisDossier = (int)(\App\Models\FraisDossier::where('id_souscription', $souscription->id)->sum('montant'));
    $modePaiement = strtoupper((string)($souscription->mode_paiement ?? ''));
    $dateDocument = optional($souscription->date_souscription ?? $souscription->created_at)->format('d/m/Y');
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Contrat de reservation</title>
    <style>
        @page { size: A4 portrait; margin: 6mm; }
        body { margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; color: #1f2a44; background: #ffffff; }
        /* Coches PDF : Arial n'embarde pas ✓ → "?" sous DomPDF */
        .pdf-glyph { font-family: "DejaVu Sans", sans-serif; font-weight: bold; }
        .watermark { position: fixed; left: 0; right: 0; top: 0; bottom: 0; z-index: 0; text-align: center; }
        .watermark img { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); width: 96%; max-width: 96%; opacity: 0.10; }
        .page {
            width: auto;
            min-height: calc(297mm - 12mm);
            margin: 0 auto;
            background: #ffffff;
            padding: 7mm 7mm 8mm;
            box-sizing: border-box;
            position: relative;
            z-index: 1;
        }
        .header { width: 100%; border-collapse: collapse; }
        .header td { vertical-align: top; }
        .logo { width: 17%; }
        .title { width: 66%; text-align: center; padding-top: 6px; }
        .qr { width: 17%; text-align: right; }
        .company-name {
            margin: 0;
            font-family: "Arial Black", Arial, Helvetica, sans-serif;
            font-size: 42px;
            font-weight: 900;
            color: #ff7200;
            line-height: 1.06;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .company-sub {
            margin: 4px 0 0;
            font-size: 13px;
            font-weight: 800;
            color: #143a7b;
            text-transform: uppercase;
            letter-spacing: 5.8px;
        }
        .contract-head { width: 62%; margin: 7px auto 10px; border-collapse: collapse; }
        .contract-head td {
            color: #fff;
            text-transform: uppercase;
            font-family: "Arial Black", Arial, Helvetica, sans-serif;
            font-weight: 900;
            font-size: 15px;
            line-height: 1;
            padding: 3px 10px 4px;
        }
        .contract-left { background: #ff7200; width: 66%; }
        .contract-right { background: #d71d26; color: #ffe84f !important; width: 34%; text-align: center; }
        .main-text {
            font-size: 11px;
            text-transform: uppercase;
            text-align: justify;
            letter-spacing: 3.8px;
            line-height: 1.32;
            margin-top: 6px;
        }
        .block-title {
            text-align: center;
            margin-top: 8px;
            color: #314868;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2.3px;
        }
        .party-row { margin-top: 1px; font-size: 12px; font-weight: 700; text-transform: uppercase; text-align: right; }
        .section { margin-top: 6px; font-size: 9px; color: #1f2a44; line-height: 1.2; }
        .line {
            display: inline-block;
            border-bottom: 1px dotted #1f2a44;
            height: 12px;
            vertical-align: baseline;
        }
        .w-xs { min-width: 34px; }
        .w-sm { min-width: 50px; }
        .w-md { min-width: 112px; }
        .w-lg { min-width: 208px; }
        .row { margin-bottom: 1px; }
        .bottom-right {
            margin-top: 2px;
            text-align: right;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .bottom-note {
            text-align: center;
            margin-top: -1px;
            font-size: 8px;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            color: #2a3d5e;
        }
        .lower {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 3.9px;
            line-height: 1.33;
            color: #1f2a44;
            text-align: justify;
            margin-top: 6px;
        }
        .lower-tight { letter-spacing: 4px; }
        .lower-title { text-align: center; font-size: 11px; text-transform: uppercase; margin-top: 6px; }
        .lower-subtitle { text-align: center; font-size: 10px; font-weight: 900; text-transform: uppercase; margin-top: 3px; }
        .objet-title { font-size: 10px; font-weight: 700; text-transform: uppercase; margin-top: 4px; }
        .objet-text {
            margin-top: 3px;
            font-size: 10px;
            line-height: 1.36;
            color: #1f2a44;
        }
        .article-title {
            text-align: center;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            margin-top: 10px;
        }
        .designation-table {
            width: 82%;
            margin: 8px auto 10px;
            border-collapse: collapse;
            font-size: 9px;
        }
        .designation-table th,
        .designation-table td {
            border: 1px solid #5d5d5d;
            padding: 6px 8px;
        }
        .designation-table th {
            text-transform: uppercase;
            font-weight: 900;
            text-align: center;
        }
        .designation-table td:first-child {
            width: 35%;
            font-weight: 700;
        }
        .center-text {
            text-align: center;
            text-transform: uppercase;
            font-size: 9px;
            margin-top: 4px;
        }
        .subtitle {
            margin-top: 5px;
            font-size: 10px;
            font-weight: 700;
        }
        .subtitle-strong {
            margin-top: 8px;
            font-size: 10px;
            font-weight: 900;
        }
        .policy-list {
            margin: 4px 0 0 16px;
            padding: 0;
            font-size: 10px;
            line-height: 1.3;
        }
        .policy-list li { margin-bottom: 3px; }
        .signature-row {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            font-size: 10px;
        }
        .signature-row td {
            width: 33.333%;
            vertical-align: bottom;
        }
        .signature-row .center { text-align: center; }
        .signature-row .right { text-align: right; }
        .sign-title { font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .sign-note { font-size: 9px; }
        .date-line { display: inline-block; border-bottom: 1px dotted #1f2a44; min-width: 130px; height: 10px; vertical-align: baseline; }
        .inline-dots {
            display: inline-block;
            border-bottom: 1px dotted #1f2a44;
            min-width: 340px;
            height: 10px;
            vertical-align: baseline;
        }
    </style>
    @include('documents.partials._brand_styles')
</head>
<body>
    @include('documents.partials._watermark')
    @php
        $logoSrc = $brand?->logoDataUri() ?? $brand?->logoAbsolutePath();
        $legalName = $brand?->displayName() ?? config('app.name');
        $tagline = $brand?->tagline ?? 'PROMOTEUR IMMOBILIER AGREE';
    @endphp
    <div class="page">
        <table class="header">
            <tr>
                <td class="logo">
                    @if($logoSrc)
                        <img src="{{ $logoSrc }}" alt="" style="max-width: 108px;">
                    @endif
                </td>
                <td class="title">
                    <h1 class="company-name">{{ $legalName }}</h1>
                    <div class="company-sub">{{ $tagline }}</div>
                </td>
                <td class="qr">
                    @if($qrSvg)
                        <img src="data:image/svg+xml;base64,{{ $qrSvg }}" alt="QR" style="width: 88px; height: 88px;">
                    @else
                        <div style="display:inline-block;width:88px;height:88px;border:1px solid #1f2a44;"></div>
                    @endif
                </td>
            </tr>
        </table>

        <table class="contract-head">
            <tr>
                <td class="contract-left">CONTRAT DE RESERVATION</td>
                <td class="contract-right">{{ $zoneProjet }}</td>
            </tr>
        </table>

        <div class="main-text">
            La societe JARE INDUSTRIES Cote d'Ivoire, promoteur immobilier agree, en abrege "JICI", societe a responsabilite limitee pluripersonnelle de dix millions (10.000.000) francs CFA ayant son siege social a Abidjan Cocody-Deux plateaux, Macaci, villa 150 lot 53 ilot19 blvd latrille 28 BP 70 Abidjan 28 et immatriculee au RCCM sous le numero CI-ABJ-03-2024-M-04211 tel : +225 21 20 80 54 20 / 07 14 83 83 83 / 07 03 94 03 14, representee, aux presentes, par Monsieur KOUAKOU Roland, son Gerant, demeurant a ABIDJAN; (Republique de COTE D'IVOIRE) Monsieur KOUAKOU Roland, agissant en sa qualite, precisee ci-dessus, a tous pouvoirs, aux fins des presentes, en vertu des statuts sociaux et de la loi
        </div>

        <div class="block-title">
            LA SOCIETE JARE INDUSTRIES COTE D'IVOIRE, ETANT ET AGISSANT COMME INDIQUE CI-DESSUS, SERA CI-APRES DENOMMEE DANS LE CORPS DU PRESENT ACTE ET POUR EN FACILITER LA REDACTION : "LE PROMOTEUR IMMOBILIER OU LE VENDEUR"
        </div>

        <div class="party-row">D'UNE PART</div>

        <div class="section">
            <div class="row">2&deg;/ <span class="line w-lg">{{ $acquereurNom }}</span></div>
            <div class="row">
                Fonction <span class="line w-lg">{{ $fonction }}</span>, demeurant a <span class="line w-lg">{{ $adresse }}</span> Boite Postale <span class="line w-md">{{ $boitePostale }}</span>
            </div>
            <div class="row">
                Ville : <span class="line w-md">{{ $ville }}</span> Pays <span class="line w-lg">{{ $pays }}</span> De nationalite <span class="line w-lg">{{ $nationalite }}</span>
            </div>
            <div class="row">
                Ne le <span class="line w-sm">{{ $dateNaissance }}</span> a <span class="line w-md">{{ $lieuNaissance }}</span>, Republique de <span class="line w-lg">{{ $pays }}</span>
            </div>
            <div class="row">
                Titulaire de la Carte Nationale d'Identite/passeport/ numero <span class="line w-lg">{{ $numeroPiece }}</span>
            </div>
            <div class="row">
                delivree le <span class="line w-sm">{{ $dateDelivrancePiece }}</span>, valable jusqu'au <span class="line w-sm">{{ $dateExpirationPiece }}</span>
            </div>
            <div class="row">
                Situation matrimoniale <span class="line w-lg">{{ $situationMatrimoniale }}</span>
            </div>
        </div>

        <div class="bottom-note">SERA CI-APRES DENOMME DANS LE CORPS DU PRESENT ACTE ET POUR EN FACILITER LA REDACTION :</div>
        <div class="bottom-right">"L'ACQUEREUR" D'AUTRE PART</div>

        <div class="lower">
            LA SOCIETE « JARE INDUSTRIES COTE D'IVOIRE » ET
            <span class="line w-lg">{{ $acquereurNom }}</span>, ETANT ET AGISSANT COMME
            INDIQUE CI-DESSUS, SERONT CI-APRES DENOMMEES DANS LE CORPS DU PRESENT ACTE ET POUR EN FACILITER LA REDACTION, ENSEMBLE,
            PARTIES" ET SEPAREMENT, PARTIE".
        </div>

        <div class="lower lower-tight" style="margin-top: 10px;">
            PRESENCE-REPRESENTATION DES PARTIES
        </div>

        <div class="objet-text">
            Il est rappele que toutes les PARTIES a la transaction, objet des presentes, sont entrees en contact physique ou par mail, par WhatsApp,
            par appel telephonique, sans exception aucune.
        </div>

        <div class="objet-text" style="text-transform: uppercase; letter-spacing: 6px;">
            CECI EXPOSE, les PARTIES ont convenu, la signature de ce contrat de reservation
            <span class="inline-dots">{{ $bien->nombre_pieces ?? $validation->nombre_pieces ?? '' }}</span>, pieces, ci-apres arretee directement entre elles en presentiel ou par mail, ou par WhatsApp, sans le concours
            ni la participation de personne ; Ce qui a lieu de la maniere suivante :
        </div>

        <div class="lower-title">VENTE A TERME</div>
        <div class="lower-subtitle">ARTICLE 1</div>
        <div class="objet-title">OBJET</div>
        <div class="objet-text">
            Par les presentes, <span class="line w-lg">{{ $acquereurNom }}</span>, reserve {{ strtolower($bienNom ?: 'un logement') }} dans la Cite « {{ $projetNom ?: 'AMAH LIBERTE' }} » de {{ $zoneProjet }} aupres de la societe denommee « JARE INDUSTRIES COTE D'IVOIRE », qui s'oblige a toutes les garanties ordinaires et de droit les plus etendues en pareille matiere
        </div>

        <div class="article-title">ARTICLE 2</div>
        <table class="designation-table">
            <tr>
                <th>DESIGNATION</th>
                <th>CARACTERISTIQUES DES LOGEMENTS</th>
            </tr>
            <tr>
                <td>Type de logement</td>
                <td>{{ $bienNom }}</td>
            </tr>
            <tr>
                <td>Superficie de terrain</td>
                <td>{{ $superficieTerrain }}</td>
            </tr>
            <tr>
                <td>Surface utile</td>
                <td>{{ $surfaceUtile }}</td>
            </tr>
        </table>

        <div class="objet-text">
            A distraire du morcellement du Titre Foncier numero
            <span style="border-bottom:1px dotted #1f2a44;">{{ $titreFoncier ?: '—' }}</span>
            de la Circonscription Fonciere
            <span style="border-bottom:1px dotted #1f2a44;">{{ $circonscriptionFoncier ?: '—' }}</span>
        </div>

        <div class="center-text">REALISATION DE LA RESERVATION</div>
        <div class="objet-text">
            La presente reservation a lieu sous les conditions ordinaires et de droit et sous celles particulieres et generales qui vont suivre ; elle est faite dans le cadre de l'article 1.584 du Code Civil, au titre des conventions prevues a cet article
        </div>

        <div class="article-title">ARTICLE 3</div>
        <div class="center-text" style="margin-top:2px;">CONDITIONS GENERALES DE LA RESERVATION</div>
        <div class="subtitle">Entree en jouissance</div>
        <div class="objet-text">
            De convention expresse entre LE VENDEUR et L'ACQUEREUR, le bien immobilier reserve sera mis a la disposition de l'ACQUEREUR, libre de toute location ou occupation quelconque dans un delai de DOUZE MOIS (12) mois, a compter du paiement integral du prix de la villa, par LE VENDEUR.
        </div>

        <div class="subtitle-strong">Transfert de propriete</div>
        <div class="objet-text">
            En application des principes prevus a l'article 1.584 du Code Civil, le transfert de propriete du bien immobilier vendu a terme n'interviendra au profit de L'ACQUEREUR, qu'a la condition que ce dernier
        </div>
        <ul class="policy-list">
            <li>Ait acquitte integralement le prix de vente du bien immobilier dont il s'agit, dans un delai d'une (1) annee constante, soit DOUZE (12) mois constants et consecutifs, a compter de la date du paiement integral du prix de vente du logement. A ce sujet, les PARTIES declarent que tous paiements anticipes de tout ou partie des mensualites ou annualites seront liberatoires.</li>
            <li>Ait execute et rempli l'ensemble des conditions tant generales que particulieres mises a sa charge aux termes des presentes.</li>
        </ul>

        <div class="subtitle-strong">Impots, Taxes et divers</div>
        <div class="objet-text">
            L'ACQUEREUR, a compter du jour de la remise des cles ou du transfert de propriete, sera tenu au paiement de tous impots, taxes et contributions de toute nature auxquels le bien immobilier vendu peut ou pourra etre assujetti. A ce sujet, VENDEUR et ACQUEREUR declarent que les impots fonciers pour l'annee en cours, seront acquittes au prorata temporis. De meme, L'ACQUEREUR ce son affaire personnelle de l'execution ou de la resiliation ou renouvellement de tous abonnements et traites passes par LE VENDEUR pour tous services publics, notamment eau, electricite, de maniere que le VENDEUR ne soit pas poursuivi, ni inquiete a cet egard. Il fera de meme son affaire personnelle de tous frais eventuels de branchement et du cout de tous abonnement et autres charges du bien immobilier ci-dessus designe.
        </div>

        <div class="subtitle-strong">Servitudes</div>
        <div class="objet-text">
            L'ACQUEREUR supportera les servitudes passives de toute nature pouvant grever le bien immobilier vendu ou l'ensemble immobilier dont il depend, sauf a s'en defendre et a profiter en retour de celles actives, le tout s'il en existe, a ces risques et perils, sans recours contre LE VENDEUR, mais sans que la presente clause puisse conferer a qui que ce soit plus de droits qu'il n'en aurait en vertu de titres reguliers non prescrits ou de la loi. A cet egard, LE VENDEUR declare qu'a sa connaissance, il n'existe aucune servitude sur le bien immobilier vendu autres que celles pouvant resulter de la situation naturelle des lieux, des dispositions d'urbanisme, Titre Foncier numero {{ $titreFoncier ?: '—' }} de la Circonscription Fonciere {{ $circonscriptionFoncier ?: '—' }}.
        </div>

        <div class="subtitle-strong">Defaillance de L'ACQUEREUR</div>
        <div class="objet-text">
            Les PARTIES s'accordent a ce que L'ACQUEREUR paie integralement le prix du logement au PROMOTEUR IMMOBILIER OU VENDEUR.
            Les PARTIES s'accordent qu'en cas de defaillance ou de desistement de L'ACQUEREUR, il sera observe un preavis de rupture de la presente reservation de TROIS (03) mois. Passe ce delai de TROIS (03) mois maximum precite, le VENDEUR procedera au remboursement des sommes versees suivant la lettre de desistement adressee par l'ACQUEREUR, deduction faite de VINGT CINQ POUR CENT (25%) ; apres avoir revendu le logement a un autre acquereur.
        </div>

        <div class="subtitle-strong">Deces du reservataire et demande de substitution</div>
        <div class="objet-text">En cas du deces du reservataire, ses ayants droits seront de plein droit :</div>
        <ul class="policy-list">
            <li>Substitues dans les termes et conditions de la reservation.</li>
            <li>Tenus, solidairement et indivisiblement, au titre des droits et obligations resultant de la reservation, a moins que les ayants droits du reservataire ne preferent pas.</li>
        </ul>

        <div class="subtitle-strong">Travaux modificatifs ou complementaires</div>
        <div class="objet-text">
            L'acquereur s'interdit de s'immiscer dans les operations de construction a la charge du vendeur et de se prevaloir de la qualite de proprietaire pour donner des instructions aux architectes et entrepreneurs. Dans le cas ou l'acquereur, avant l'achevement des travaux, desirerait que des modifications fussent apportees ou que des travaux supplementaires fussent executes, il devra s'adresser au promoteur par ecrit. Celui-ci appreciera si les modifications demandees sont realisables et le cas echeant, comme au cas de demande de travaux supplementaires, etablira, en accord avec l'acquereur, par voie d'avenant ecrit prealable, la nature des modifications ou des travaux supplementaires, leur cout, leurs conditions de paiement et, le cas echeant, l'incidence desdits travaux sur le delai de livraison ci-dessus prevu.
        </div>

        <div class="subtitle-strong">Achevements des travaux</div>
        <div class="objet-text">Le vendeur s'obligera</div>
        <ul class="policy-list">
            <li>A achever ces travaux dans le delai precise aux conditions particulieres.</li>
            <li>A effectuer les travaux de parachevement dans les delais compatibles avec la nature des travaux a realiser.<br>
                Toutefois, le delai ci-dessus sera majore, en cas de force majeure ou de survenance d'une cause legitime de suspension des travaux notamment des jours de retard resultant :
            </li>
            <li>D'une greve, qu'elle soit generale ou particuliere au batiment et a ses industries annexes ou speciales aux entreprises travaillant sur le chantier.</li>
            <li>De la cessation de paiement, du redressement ou de la liquidation judiciaire d'une entreprise participant aux travaux ou l'un des fournisseurs.</li>
            <li>D'injonctions administratives ou judiciaires de suspendre ou d'arreter les travaux dans la mesure ou celles-ci ne seraient pas fondees sur des fautes ou des negligences du vendeur.</li>
            <li>Des travaux modificatifs ou complementaires demandees par l'acquereur et accepte par le vendeur conformement aux dispositions prevues ci-apres.</li>
            <li>Des troubles resultant d'hostilites, revolutions, cataclysmes naturels, accident de chantier des retards de paiement de l'acquereur.</li>
        </ul>

        <div class="article-title">ARTICLE 4</div>
        <div class="center-text" style="margin-top:2px;">CONDITIONS PARTICULIERES ET FINANCIERES</div>
        <div class="subtitle">Modalites Financieres de la vente a terme</div>
        <div class="objet-text">
            La vente a terme, objet des presentes, a lieu sous les conditions financieres suivantes a l'execution desquelles L'ACQUEREUR s'oblige expressement. La presente vente est consentie et acceptee moyennant le prix principal de @if($prixVente > 0)<span style="border-bottom:1px dotted #1f2a44;">{{ $prixVenteLettres !== '' ? $prixVenteLettres : '……………………………………………………' }}</span> (<span style="border-bottom:1px dotted #1f2a44;">{{ $prixVenteFormate }}</span>)@else<span class="inline-dots" style="min-width:220px;"></span> (<span class="inline-dots" style="min-width:120px;"></span>)@endif DE FRANCS CFA. Lequel prix sera paye comme suit :
        </div>
        <ul class="policy-list">
            <li>@if($modePaiement === 'CREDIT_BANCAIRE')<span class="pdf-glyph">&#10003;</span> @endif Credit Bancaire</li>
            <li>@if($modePaiement === 'CASH')<span class="pdf-glyph">&#10003;</span> @endif Cash</li>
            <li>@if($modePaiement === 'TEMPERAMENT')<span class="pdf-glyph">&#10003;</span> @endif Temperament</li>
            <li>@if($modePaiement === 'AVANCEMENT_TRAVAUX')<span class="pdf-glyph">&#10003;</span> @endif Avancement des travaux</li>
        </ul>

        <div class="article-title">ARTICLE 5</div>
        <div class="center-text" style="margin-top:2px;">ELECTION DE DOMICILE</div>
        <div class="objet-text">
            Pour l'execution des presentes et de leurs suites, les parties elisent domicile, a savoir :
        </div>
        <ul class="policy-list">
            <li>Le VENDEUR en son siege sus-indique ;</li>
            <li>L'ACQUEREUR en son domicile sis {{ $adresse ?: 'sus-enonce' }}.</li>
        </ul>

        <div class="article-title">ARTICLE 5</div>
        <div class="center-text" style="margin-top:2px;">ATTRIBUTION DE JURIDICTION</div>
        <div class="objet-text">
            Tout differends pouvant survenir entre les parties concernant l'interpretation ou l'execution des presentes, sera resolu par la voie amiable.
        </div>
        <div class="objet-text">
            Au cas ou ces differends ne pourraient etre ainsi resolus, le Tribunal du Commerce d'ABIDJAN sera seul competent pour regler toutes contestations qui pourront surgir a l'occasion des presentes.
        </div>

        <table class="signature-row">
            <tr>
                <td>
                    <div class="sign-title">POUR L'ACQUEREUR</div>
                    <div class="sign-note">Signature precedee de la mention</div>
                    <div class="sign-note">« LU COMPRIS ET APPROUVE »</div>
                </td>
                <td class="center">
                    <div style="font-size:11px;">Fait a ABIDJAN le <span class="date-line">{{ $dateDocument }}</span></div>
                    <div>(En deux exemplaires originaux remis aux parties)</div>
                </td>
                <td class="right">
                    <div class="sign-title">POUR JARE INDUSTRIES COTE D'IVOIRE</div>
                </td>
            </tr>
        </table>
    </div>

    
</body>
</html>

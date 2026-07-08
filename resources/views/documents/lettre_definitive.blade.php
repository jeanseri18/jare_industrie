<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attestation de fin de paiement</title>
    <style>
        @page { margin: 0.6cm 1cm; }
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.35; color: #1f2a44; }
        .page { position: relative; }
        .header { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .header td { vertical-align: top; }
        .logo { width: 18%; }
        .title { width: 64%; text-align: center; padding-top: 6px; }
        .qr { width: 18%; text-align: right; }
        .company-name { font-size: 26px; font-weight: 900; color: #ff7200; text-transform: uppercase; margin: 0; letter-spacing: 0.5px; }
        .company-sub { font-size: 18px; font-weight: 900; color: #1e3a8a; text-transform: uppercase; margin: 4px 0 0; letter-spacing: 4px; }
        .stamp-wrap { width: 100%; text-align: center; margin: 14px 0 16px; }
        .stamp { display: inline-block; border: 2px solid #ff7200; padding: 10px 16px; }
        .stamp-title { font-size: 44px; font-weight: 900; color: #ff7200; text-transform: uppercase; line-height: 0.9; }
        .stamp-sub { font-size: 32px; font-weight: 900; color: #ff7200; text-transform: uppercase; line-height: 0.9; }
        .stamp-underline { height: 5px; background: #1e3a8a; margin-top: 6px; }
        .text { text-align: justify; font-size: 15px; color: #1f2a44; }
        .text strong { font-weight: 900; }
        .atteste { font-weight: 900; text-decoration: underline; margin-top: 10px; }
        .line-item { display: table; width: 100%; margin: 4px 0; }
        .line-item .left { display: table-cell; width: 30%; font-weight: 900; text-transform: uppercase; color: #1f2a44; }
        .line-item .dots { display: table-cell; width: 70%; border-bottom: 2px dotted #1e3a8a; }
        .inline-dots { display: inline-block; border-bottom: 2px dotted #1e3a8a; min-width: 280px; height: 18px; vertical-align: baseline; }
        .inline-dots.small { min-width: 180px; }
        .muted { color: #1f2a44; }
        .date-box { width: 46%; margin-left: auto; margin-top: 18px; font-size: 14px; font-weight: 900; color: #1f2a44; }
        .sign-row { width: 100%; margin-top: 18px; border-collapse: collapse; }
        .sign-row td { width: 50%; vertical-align: top; padding-top: 18px; }
        .sign-title { font-size: 20px; font-weight: 900; color: #1e3a8a; text-decoration: underline; }
        .sign-center { text-align: center; margin-top: 50px; }
        .sign-center .sign-title { font-size: 22px; }
        .footer { position: fixed; left: 10px; right: 10px; bottom: 8px; height: 62px; background: #1e3a8a; color: #fff; padding: 10px 14px; font-size: 10px; line-height: 1.25; text-align: center; border-top: 4px solid #ff7200; }
        .footer strong { font-weight: 900; }
        .watermark { position: fixed; left: 50%; top: 56%; transform: translate(-50%, -50%); opacity: 0.10; width: 96%; text-align: center; }
        .watermark img { width: 100%; }
        .content-pad { padding-bottom: 82px; }
        /* Coches : Arial (PDF) n'embarque pas ✓ → affichage "?" sous DomPDF */
        .pdf-glyph { font-family: "DejaVu Sans", sans-serif; font-weight: bold; margin-right: 4px; }
    </style>
    @include('documents.partials._brand_styles')
</head>
<body>
    @include('documents.partials._watermark')
    @php
        $qrUrl = \Illuminate\Support\Facades\URL::signedRoute('public.souscriptions.lettre-definitive', ['souscription' => $souscription->id]);
        $numero = $validation->lot ?? ($souscription->attributionLot->numero_villa ?? ($souscription->attributionLot->lot ?? ''));
        $ilot = $validation->ilot_attribue ?? ($souscription->attributionLot->ilot ?? '');
        $lot = $validation->lot ?? ($souscription->attributionLot->lot ?? '');
        $pieces = $validation->nombre_pieces
            ?? $souscription->nb_pieces
            ?? $souscription->bienImmobilier?->nbre_piece
            ?? '';
        $frais = \App\Models\FraisDossier::where('id_souscription', $souscription->id)->sum('montant');
        $client = $souscription->client;
        $nomClient = strtoupper(trim(($client->nom ?? $validation->nom_client ?? $souscription->nom ?? '') . ' ' . ($client->prenom ?? $client->prenoms ?? $validation->prenom_client ?? $souscription->prenom ?? '')));
        $prixVente = (int)($souscription->prix_logement ?? $souscription->valeur_souscription ?? 0);
        $prixVenteFormate = $prixVente ? number_format($prixVente, 0, ',', ' ') : '';
        $toFrenchWords = function (int $n) {
            $units = ['', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf'];
            $teens = [10 => 'dix', 11 => 'onze', 12 => 'douze', 13 => 'treize', 14 => 'quatorze', 15 => 'quinze', 16 => 'seize', 17 => 'dix-sept', 18 => 'dix-huit', 19 => 'dix-neuf'];
            $tens = ['', 'dix', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante', 'quatre-vingt', 'quatre-vingt'];

            $twoDigits = function (int $x) use ($units, $teens, $tens) {
                if ($x < 10) return $units[$x];
                if ($x < 20) return $teens[$x];
                $d = intdiv($x, 10);
                $u = $x % 10;

                if ($d === 7) {
                    return 'soixante' . ($u === 1 ? ' et ' : '-') . ($u === 0 ? 'dix' : ($teens[10 + $u] ?? ''));
                }
                if ($d === 9) {
                    return 'quatre-vingt' . '-' . ($u === 0 ? 'dix' : ($teens[10 + $u] ?? ''));
                }
                if ($d === 8) {
                    if ($u === 0) return 'quatre-vingts';
                    return 'quatre-vingt' . '-' . $units[$u];
                }
                if ($u === 0) return $tens[$d];
                if ($u === 1 && $d !== 8) return $tens[$d] . ' et un';
                return $tens[$d] . '-' . $units[$u];
            };

            $threeDigits = function (int $x) use ($units, $twoDigits) {
                $c = intdiv($x, 100);
                $r = $x % 100;
                $out = '';
                if ($c > 0) {
                    if ($c === 1) $out = 'cent';
                    else $out = $units[$c] . ' cent';
                    if ($r === 0 && $c > 1) $out .= 's';
                }
                if ($r > 0) {
                    $out = trim($out . ' ' . $twoDigits($r));
                }
                return $out;
            };

            if ($n === 0) return 'zéro';
            if ($n < 0) return 'moins ' . $toFrenchWords(abs($n));

            $parts = [];
            $millions = intdiv($n, 1000000);
            $n %= 1000000;
            $thousands = intdiv($n, 1000);
            $rest = $n % 1000;

            if ($millions > 0) {
                $parts[] = ($millions === 1 ? 'un million' : $threeDigits($millions) . ' millions');
            }
            if ($thousands > 0) {
                if ($thousands === 1) $parts[] = 'mille';
                else $parts[] = $threeDigits($thousands) . ' mille';
            }
            if ($rest > 0) {
                $parts[] = $threeDigits($rest);
            }
            return trim(implode(' ', $parts));
        };

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
        if ($prixVente > 0 && $prixVenteLettres === '') {
            $prixVenteLettres = mb_strtoupper($toFrenchWords($prixVente));
        }
        $fraisFormate = $frais ? number_format((int)$frais, 0, ',', ' ') : '';
        $fraisLettres = '';
        if ((int)$frais > 0 && extension_loaded('intl')) {
            try {
                $fmt2 = new \NumberFormatter('fr_FR', \NumberFormatter::SPELL_OUT);
                $fmt2->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 0);
                $fraisLettres = mb_strtoupper((string) $fmt2->format((int)$frais));
            } catch (\Throwable $e) {
                $fraisLettres = '';
            }
        }
        if ((int)$frais > 0 && $fraisLettres === '') {
            $fraisLettres = mb_strtoupper($toFrenchWords((int)$frais));
        }
        $dateDoc = optional($validation->date_dernier_paiement ?? $souscription->updated_at ?? $souscription->created_at)->format('d/m/Y');
    @endphp

    <div class="page content-pad">
        @php
            $logoSrc = $brand?->logoDataUri() ?? $brand?->logoAbsolutePath();
            $legalName = $brand?->displayName() ?? config('app.name');
            $tagline = $brand?->tagline ?? 'PROMOTEUR IMMOBILIER AGRÉÉ';
            $directorName = $brand?->director_name ?? 'Directeur Général';
        @endphp
        <table class="header">
            <tr>
                <td class="logo">
                    @if($logoSrc)
                        <img src="{{ $logoSrc }}" alt="" style="max-width: 150px;">
                    @endif
                </td>
                <td class="title">
                    <div class="company-name">{{ $legalName }}</div>
                    <div class="company-sub">{{ $tagline }}</div>
                </td>
                <td class="qr">
                    <img src="data:image/svg+xml;base64, {{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(110)->generate($qrUrl)) }}">
                </td>
            </tr>
        </table>

        <div class="stamp-wrap">
            <div class="stamp">
                <div class="stamp-title">ATTESTATION</div>
                <div class="stamp-sub">DE FIN DE PAIEMENT</div>
                <div class="stamp-underline"></div>
            </div>
        </div>

        <div class="text">
            Je soussigné, Monsieur <strong>{{ $directorName }}</strong>, Directeur Général de la société <strong>{{ strtoupper($legalName) }}</strong>,
            Promoteur Immobilier Agréé sous le N°21-0041/MCLU/DGLCV/CAPPI/Olga du 12 juillet 2021. Ayant son siège social à
            ABIDJAN-COCODY, LES DEUX PLATEAUX, MACACI, Villa 150, lot 53, Boulevard latrille, 28 Boîte Postale 70 ABIDJAN 28 République de
            COTE D’IVOIRE.
        </div>

        <div class="atteste">ATTESTE QUE :</div>

        <div class="text muted" style="margin-top: 6px;">
            Le logement de la Cité <strong>{{ $souscription->projet->nom ?? '...' }}</strong> à <strong>{{ $souscription->projet->localisation ?? '...' }}</strong> :
        </div>

        <div style="margin-top: 12px;">
            <div class="line-item">
                <div class="left"><span class="pdf-glyph">&#10003;</span> NUMÉRO</div>
                <div class="dots"><span style="padding-left:10px; font-weight:900;">{{ $numero ?: '—' }}</span></div>
            </div>
            <div class="line-item">
                <div class="left"><span class="pdf-glyph">&#10003;</span> ILOT</div>
                <div class="dots"><span style="padding-left:10px; font-weight:900;">{{ $ilot ?: '—' }}</span></div>
            </div>
            <div class="line-item">
                <div class="left"><span class="pdf-glyph">&#10003;</span> LOT</div>
                <div class="dots"><span style="padding-left:10px; font-weight:900;">{{ $lot ?: '—' }}</span></div>
            </div>
            <div class="line-item">
                <div class="left"><span class="pdf-glyph">&#10003;</span> NOMBRE DE PIECES</div>
                <div class="dots"><span style="padding-left:10px; font-weight:900;">{{ $pieces ?: '—' }}</span></div>
            </div>
        </div>

        <div class="text" style="margin-top: 14px;">
            A été vendu à <span class="inline-dots">{{ $nomClient ?: '—' }}</span>
        </div>
        <div class="text" style="margin-top: 8px;">
            Le prix de vente du logement convenu de <span class="inline-dots">{{ $prixVenteLettres ?: '—' }}</span>
        </div>
        <div class="text" style="margin-top: 6px;">
            (<span class="inline-dots small">{{ $prixVenteFormate ?: '—' }}</span>) DE FRANCS CFA.
        </div>
        <div class="text" style="margin-top: 8px;">
            Et les frais de dossier <span class="inline-dots small">{{ $fraisLettres ?: '—' }}</span> (<span class="inline-dots small">{{ $fraisFormate ?: '—' }}</span>) DE FRANCS CFA ont été
            entièrement réglés dans nos caisses.
        </div>

        <div class="text" style="margin-top: 12px;">
            Les formalités de signature de l’acte de vente étant en cours, la présente attestation lui est délivrée pour servir et valoir ce que de droit.
        </div>

        <div class="date-box">
            Fait à Abidjan le <span class="inline-dots small">{{ $dateDoc ?: '—' }}</span><br>
            <span style="font-weight: 700;">(En deux copies originales)</span>
        </div>

        <table class="sign-row">
            <tr>
                <td>
                    <div class="sign-title">Le Directeur Commercial</div>
                </td>
                <td style="text-align:right;">
                    <div class="sign-title">La Directrice Financière et comptable</div>
                </td>
            </tr>
        </table>

        <div class="sign-center">
            <div class="sign-title">Le Président Directeur Général</div>
        </div>
    </div>

    @include('documents.partials._pdf_footer')
</body>
</html>

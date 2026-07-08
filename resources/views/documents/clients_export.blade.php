<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export clients</title>
    <style>
        @page { margin: 0.8cm 1cm; }
        body { font-family: Arial, sans-serif; font-size: 10px; color: #111; margin: 0; padding: 0; }
        .watermark { position: fixed; left: 0; right: 0; top: 0; bottom: 0; z-index: 0; text-align: center; }
        .watermark img { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); width: 96%; max-width: 96%; opacity: 0.06; }
        .content { position: relative; z-index: 1; }
        .title { text-align: center; font-size: 16px; font-weight: 700; margin-bottom: 6px; color: #004A80; }
        .subtitle { text-align: center; font-size: 11px; margin-bottom: 10px; }
        .filters { font-size: 9px; margin-bottom: 10px; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 6px; vertical-align: top; }
        th { background: #f2f6ff; color: #004A80; text-transform: uppercase; font-size: 9px; }
        .muted { color: #666; }
    </style>
    @include('documents.partials._brand_styles')
</head>
<body>
    @include('documents.partials._watermark')
    <div class="content">
    @php
        $qrUrl = route('dg.clients.export.pdf', array_filter($filters ?? []));
        $legalName = $brand?->displayName() ?? config('app.name');
    @endphp

    <div style="display:flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <div class="title">{{ $legalName }}</div>
            <div class="subtitle">Export des clients</div>
        </div>
        <div style="text-align:right;">
            <img src="data:image/svg+xml;base64, {{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(70)->generate($qrUrl)) }}" >
        </div>
    </div>

    <div class="filters">
        <strong>Filtres :</strong>
        <span class="muted">
            Recherche={{ $filters['search'] ?? '-' }},
            Date={{ $filters['date_creation'] ?? '-' }},
            Projet={{ $filters['projet_id'] ?? '-' }},
            Mutuelle={{ $filters['mutuelle_id'] ?? '-' }}
        </span>
        <span style="float:right;" class="muted">Généré le {{ now()->format('d/m/Y H:i') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Référence</th>
                <th>Nom et Prénom</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Catégorie</th>
                <th>Mutuelle</th>
                <th>Projet</th>
                <th>Date création</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{{ $client->id }}</td>
                    <td>{{ $client->ref_client }}</td>
                    <td>{{ $client->nom_prenom }}</td>
                    <td>{{ $client->telephone }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->categorie_client }}</td>
                    <td>{{ $client->mutuelle?->nom ?? '' }}</td>
                    <td>{{ $client->lastSouscription?->projet?->nom ?? '' }}</td>
                    <td>{{ $client->created_at ? $client->created_at->format('d/m/Y') : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</body>
</html>

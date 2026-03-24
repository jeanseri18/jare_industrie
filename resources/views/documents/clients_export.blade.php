<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export clients</title>
    <style>
        @page { margin: 0.8cm 1cm; }
        body { font-family: Arial, sans-serif; font-size: 10px; color: #111; }
        .title { text-align: center; font-size: 16px; font-weight: 700; margin-bottom: 6px; color: #004A80; }
        .subtitle { text-align: center; font-size: 11px; margin-bottom: 10px; }
        .filters { font-size: 9px; margin-bottom: 10px; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 6px; vertical-align: top; }
        th { background: #f2f6ff; color: #004A80; text-transform: uppercase; font-size: 9px; }
        .muted { color: #666; }
    </style>
</head>
<body>
    <div class="title">JARE INDUSTRIES</div>
    <div class="subtitle">Export des clients</div>

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
</body>
</html>

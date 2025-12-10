@extends('layouts.dg')

@section('title', 'Détails du Client')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-user me-2"></i>
            Détails du Client
        </div>
        <a href="{{ route('dg.clients.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
    
    <div style="padding: 20px;">
        <div class="row">
            <div class="col-md-6">
                <div class="card-custom mb-3">
                    <div class="card-header-custom">
                        <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Informations Personnelles</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="mb-2">
                            <strong>Nom et Prénom:</strong> {{ $client->nom_prenom }}
                        </div>
                        <div class="mb-2">
                            <strong>Date de Naissance:</strong> {{ $client->date_naissance ? \Carbon\Carbon::parse($client->date_naissance)->format('d/m/Y') : 'Non définie' }}
                        </div>
                        <div class="mb-2">
                            <strong>Lieu de Naissance:</strong> {{ $client->lieu_naissance ?? 'Non défini' }}
                        </div>
                        <div class="mb-2">
                            <strong>Nationalité:</strong> {{ $client->nationalite ?? 'Non définie' }}
                        </div>
                        <div class="mb-2">
                            <strong>Situation Matrimoniale:</strong> {{ ucfirst($client->situation_matrimoniale ?? 'Non définie') }}
                        </div>
                        <div class="mb-2">
                            <strong>Nombre d'Enfants:</strong> {{ $client->nombre_enfants ?? 0 }}
                        </div>
                        <div class="mb-2">
                            <strong>Ayant Droit:</strong> {{ $client->ayant_droit ?? 'Non défini' }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card-custom mb-3">
                    <div class="card-header-custom">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations de Contact</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="mb-2">
                            <strong>Téléphone:</strong> {{ $client->telephone ?? 'Non défini' }}
                        </div>
                        <div class="mb-2">
                            <strong>Email:</strong> {{ $client->email ?? 'Non défini' }}
                        </div>
                        <div class="mb-2">
                            <strong>Salaire Mensuel:</strong> {{ number_format($client->salaire_mensuel ?? 0, 0, ',', ' ') }} FCFA
                        </div>
                        <div class="mb-2">
                            <strong>Catégorie Client:</strong> 
                            <span class="badge-custom badge-info">
                                {{ ucfirst($client->categorie_client ?? 'Non définie') }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <strong>Mutuelle:</strong> {{ $client->mutuelle->nom ?? 'Aucune' }}
                        </div>
                        <div class="mb-2">
                            <strong>Référence Client:</strong> {{ $client->ref_client ?? 'Non définie' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i>Souscriptions</h5>
            </div>
            <div class="card-body-custom">
                @if($client->souscriptions->count() > 0)
                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Référence Souscription</th>
                                    <th>Programme</th>
                                    <th>Type Logement</th>
                                    <th>Statut</th>
                                    <th>Date de Souscription</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($client->souscriptions as $souscription)
                                <tr>
                                    <td>{{ $souscription->id }}</td>
                                    <td>{{ $souscription->ref_souscription ?? 'Non définie' }}</td>
                                    <td>{{ $souscription->nom_programme ?? $souscription->programme ?? 'Non défini' }}</td>
                                    <td>{{ ucfirst($souscription->type_logement ?? 'Non défini') }}</td>
                                    <td>
                                        <span class="badge-custom badge-{{ $souscription->statut == 'active' ? 'success' : ($souscription->statut == 'inactive' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($souscription->statut ?? 'Non défini') }}
                                        </span>
                                    </td>
                                    <td>{{ $souscription->created_at ? \Carbon\Carbon::parse($souscription->created_at)->format('d/m/Y H:i') : 'Non définie' }}</td>
                                    <td>
                                    
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucune souscription trouvée pour ce client.
                    </div>
                @endif
            </div>
        </div>
        
        <div class="mt-3 text-center">
            <a href="{{ route('dg.clients.edit', $client) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Modifier le Client
            </a>
        </div>
    </div>
</div>
@endsection@extends('layouts.dg')

@section('title', 'Détails du Client')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-user me-2"></i>
            Détails du Client
        </div>
        <a href="{{ route('dg.clients.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
    
    <div style="padding: 20px;">
        <!-- Carte d'identité du client -->
        <div class="card-custom mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
            <div class="card-body-custom" style="padding: 30px;">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div style="width: 100px; height: 100px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #667eea; font-size: 40px; font-weight: bold; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            {{ strtoupper(substr($client->nom_prenom, 0, 1)) }}
                        </div>
                    </div>
                    <div class="col">
                        <h2 style="margin: 0 0 10px 0; color: white; font-weight: 700;">{{ $client->nom_prenom }}</h2>
                        <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: center;">
                            <span style="background: rgba(255,255,255,0.2); color: white; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; backdrop-filter: blur(10px);">
                                <i class="fas fa-tag me-1"></i>{{ ucfirst($client->categorie_client ?? 'Non définie') }}
                            </span>
                            <span style="color: rgba(255,255,255,0.9); font-size: 14px;">
                                <i class="fas fa-fingerprint me-1"></i>
                                {{ $client->ref_client ?? 'Non définie' }}
                            </span>
                            <span style="color: rgba(255,255,255,0.9); font-size: 14px;">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Membre depuis {{ $client->created_at ? \Carbon\Carbon::parse($client->created_at)->format('d/m/Y') : '-' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('dg.clients.edit', $client) }}" class="btn btn-light" style="border-radius: 8px; padding: 10px 20px; font-weight: 600;">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card-custom" style="border-left: 4px solid #667eea;">
                    <div class="card-body-custom" style="padding: 20px;">
                        <div style="color: #7f8c8d; font-size: 13px; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">Souscriptions</div>
                        <div style="font-size: 32px; font-weight: 700; color: #667eea;">{{ $client->souscriptions->count() }}</div>
                        <div style="color: #95a5a6; font-size: 12px; margin-top: 5px;">
                            <i class="fas fa-file-contract me-1"></i>Total actif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card-custom" style="border-left: 4px solid #f5576c;">
                    <div class="card-body-custom" style="padding: 20px;">
                        <div style="color: #7f8c8d; font-size: 13px; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">Salaire Mensuel</div>
                        <div style="font-size: 24px; font-weight: 700; color: #f5576c;">{{ number_format($client->salaire_mensuel ?? 0, 0, ',', ' ') }}</div>
                        <div style="color: #95a5a6; font-size: 12px; margin-top: 5px;">
                            <i class="fas fa-coins me-1"></i>FCFA
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card-custom" style="border-left: 4px solid #4facfe;">
                    <div class="card-body-custom" style="padding: 20px;">
                        <div style="color: #7f8c8d; font-size: 13px; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">Enfants</div>
                        <div style="font-size: 32px; font-weight: 700; color: #4facfe;">{{ $client->nombre_enfants ?? 0 }}</div>
                        <div style="color: #95a5a6; font-size: 12px; margin-top: 5px;">
                            <i class="fas fa-users me-1"></i>Personnes à charge
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card-custom" style="border-left: 4px solid #43e97b;">
                    <div class="card-body-custom" style="padding: 20px;">
                        <div style="color: #7f8c8d; font-size: 13px; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">Statut</div>
                        <div style="font-size: 20px; font-weight: 700; color: #43e97b;">{{ ucfirst($client->situation_matrimoniale ?? 'N/A') }}</div>
                        <div style="color: #95a5a6; font-size: 12px; margin-top: 5px;">
                            <i class="fas fa-heart me-1"></i>Matrimonial
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card-custom mb-3">
                    <div class="card-header-custom">
                        <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Informations Personnelles</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="mb-3" style="padding-bottom: 12px; border-bottom: 1px solid #ecf0f1;">
                            <strong style="color: #7f8c8d; font-size: 13px; display: block; margin-bottom: 5px;">Nom et Prénom</strong>
                            <span style="color: #2c3e50; font-size: 15px;">{{ $client->nom_prenom }}</span>
                        </div>
                        <div class="mb-3" style="padding-bottom: 12px; border-bottom: 1px solid #ecf0f1;">
                            <strong style="color: #7f8c8d; font-size: 13px; display: block; margin-bottom: 5px;">Date de Naissance</strong>
                            <span style="color: #2c3e50; font-size: 15px;">
                                <i class="fas fa-birthday-cake me-2" style="color: #667eea;"></i>
                                {{ $client->date_naissance ? \Carbon\Carbon::parse($client->date_naissance)->format('d/m/Y') : 'Non définie' }}
                            </span>
                        </div>
                        <div class="mb-3" style="padding-bottom: 12px; border-bottom: 1px solid #ecf0f1;">
                            <strong style="color: #7f8c8d; font-size: 13px; display: block; margin-bottom: 5px;">Lieu de Naissance</strong>
                            <span style="color: #2c3e50; font-size: 15px;">
                                <i class="fas fa-map-marker-alt me-2" style="color: #f5576c;"></i>
                                {{ $client->lieu_naissance ?? 'Non défini' }}
                            </span>
                        </div>
                        <div class="mb-3" style="padding-bottom: 12px; border-bottom: 1px solid #ecf0f1;">
                            <strong style="color: #7f8c8d; font-size: 13px; display: block; margin-bottom: 5px;">Nationalité</strong>
                            <span style="color: #2c3e50; font-size: 15px;">
                                <i class="fas fa-flag me-2" style="color: #43e97b;"></i>
                                {{ $client->nationalite ?? 'Non définie' }}
                            </span>
                        </div>
                        <div class="mb-3" style="padding-bottom: 12px; border-bottom: 1px solid #ecf0f1;">
                            <strong style="color: #7f8c8d; font-size: 13px; display: block; margin-bottom: 5px;">Situation Matrimoniale</strong>
                            <span style="color: #2c3e50; font-size: 15px;">
                                <i class="fas fa-heart me-2" style="color: #f093fb;"></i>
                                {{ ucfirst($client->situation_matrimoniale ?? 'Non définie') }}
                            </span>
                        </div>
                        <div class="mb-3" style="padding-bottom: 12px; border-bottom: 1px solid #ecf0f1;">
                            <strong style="color: #7f8c8d; font-size: 13px; display: block; margin-bottom: 5px;">Nombre d'Enfants</strong>
                            <span style="color: #2c3e50; font-size: 15px;">
                                <i class="fas fa-baby me-2" style="color: #4facfe;"></i>
                                {{ $client->nombre_enfants ?? 0 }}
                            </span>
                        </div>
                        <div class="mb-0">
                            <strong style="color: #7f8c8d; font-size: 13px; display: block; margin-bottom: 5px;">Ayant Droit</strong>
                            <span style="color: #2c3e50; font-size: 15px;">
                                <i class="fas fa-user-shield me-2" style="color: #764ba2;"></i>
                                {{ $client->ayant_droit ?? 'Non défini' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card-custom mb-3">
                    <div class="card-header-custom">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations de Contact</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="mb-3" style="padding-bottom: 12px; border-bottom: 1px solid #ecf0f1;">
                            <strong style="color: #7f8c8d; font-size: 13px; display: block; margin-bottom: 5px;">Téléphone</strong>
                            <span style="color: #2c3e50; font-size: 15px;">
                                <i class="fas fa-phone me-2" style="color: #667eea;"></i>
                                {{ $client->telephone ?? 'Non défini' }}
                            </span>
                        </div>
                        <div class="mb-3" style="padding-bottom: 12px; border-bottom: 1px solid #ecf0f1;">
                            <strong style="color: #7f8c8d; font-size: 13px; display: block; margin-bottom: 5px;">Email</strong>
                            <span style="color: #2c3e50; font-size: 15px;">
                                <i class="fas fa-envelope me-2" style="color: #f5576c;"></i>
                                {{ $client->email ?? 'Non défini' }}
                            </span>
                        </div>
                        <div class="mb-3" style="padding-bottom: 12px; border-bottom: 1px solid #ecf0f1;">
                            <strong style="color: #7f8c8d; font-size: 13px; display: block; margin-bottom: 5px;">Salaire Mensuel</strong>
                            <span style="color: #2c3e50; font-size: 15px; font-weight: 600;">
                                <i class="fas fa-coins me-2" style="color: #43e97b;"></i>
                                {{ number_format($client->salaire_mensuel ?? 0, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        <div class="mb-3" style="padding-bottom: 12px; border-bottom: 1px solid #ecf0f1;">
                            <strong style="color: #7f8c8d; font-size: 13px; display: block; margin-bottom: 5px;">Catégorie Client</strong>
                            <span class="badge-custom badge-info">
                                {{ ucfirst($client->categorie_client ?? 'Non définie') }}
                            </span>
                        </div>
                        <div class="mb-3" style="padding-bottom: 12px; border-bottom: 1px solid #ecf0f1;">
                            <strong style="color: #7f8c8d; font-size: 13px; display: block; margin-bottom: 5px;">Mutuelle</strong>
                            <span style="color: #2c3e50; font-size: 15px;">
                                <i class="fas fa-hands-helping me-2" style="color: #764ba2;"></i>
                                {{ $client->mutuelle->nom ?? 'Aucune' }}
                            </span>
                        </div>
                        <div class="mb-0">
                            <strong style="color: #7f8c8d; font-size: 13px; display: block; margin-bottom: 5px;">Référence Client</strong>
                            <span style="color: #2c3e50; font-size: 15px; font-family: monospace; background: #f8f9fa; padding: 4px 8px; border-radius: 4px;">
                                <i class="fas fa-barcode me-2" style="color: #4facfe;"></i>
                                {{ $client->ref_client ?? 'Non définie' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i>Souscriptions</h5>
            </div>
            <div class="card-body-custom">
                @if($client->souscriptions->count() > 0)
                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Référence Souscription</th>
                                    <th>Programme</th>
                                    <th>Type Logement</th>
                                    <th>Coût Total</th>
                                    <th>Statut</th>
                                    <th>Date de Souscription</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($client->souscriptions as $souscription)
                                <tr>
                                    <td><strong>#{{ $souscription->id }}</strong></td>
                                    <td>
                                        <span style="color: #667eea; font-weight: 500;">{{ $souscription->ref_souscription ?? 'Non définie' }}</span>
                                    </td>
                                    <td>{{ $souscription->nom_programme ?? $souscription->programme ?? 'Non défini' }}</td>
                                    <td>
                                        <i class="fas fa-home me-1" style="color: #f5576c;"></i>
                                        {{ ucfirst($souscription->type_logement ?? 'Non défini') }}
                                    </td>
                                    <td><strong>{{ number_format($souscription->cout_total ?? 0, 0, ',', ' ') }} FCFA</strong></td>
                                    <td>
                                        <span class="badge-custom badge-{{ $souscription->statut == 'active' ? 'success' : ($souscription->statut == 'inactive' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($souscription->statut ?? 'Non défini') }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar me-1" style="color: #95a5a6;"></i>
                                        {{ $souscription->created_at ? \Carbon\Carbon::parse($souscription->created_at)->format('d/m/Y H:i') : 'Non définie' }}
                                    </td>
                                    <td>
                                    
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucune souscription trouvée pour ce client.
                    </div>
                @endif
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <a href="{{ route('dg.clients.edit', $client) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Modifier le Client
            </a>
            <a href="{{ route('dg.clients.index') }}" class="btn btn-secondary ms-2">
                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection
@extends('layouts.dg')

@section('title', 'Détails du Client')

@section('content')
<x-page-header :title="$client->nom_prenom" subtitle="Détails du client">
    <x-slot:actions>
        @if($clientUser ?? null)
            <a href="{{ route('dg.clients.password.edit', $client) }}" class="btn-secondary">
                <i class="fas fa-key me-1"></i> Mot de passe
            </a>
        @endif
        <a href="{{ route('dg.clients.edit', $client) }}" class="btn-secondary">
            <i class="fas fa-edit me-1"></i> Modifier
        </a>
        <a href="{{ route('dg.clients.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
        </a>
    </x-slot:actions>
</x-page-header>

<x-alert />

@if(session('client_credentials'))
    @php $creds = session('client_credentials'); @endphp
    <div class="app-alert alert alert-success mb-4">
        <strong>Identifiants du compte client</strong>
        <ul class="mb-0 mt-2">
            <li>Client : {{ $creds['nom_client'] ?? $client->nom_prenom }}</li>
            <li>Email : <code>{{ $creds['email'] }}</code></li>
            <li>Mot de passe : <code>{{ $creds['password'] }}</code></li>
        </ul>
        <p class="mb-0 mt-2 text-sm">Communiquez ces identifiants au client pour qu'il se connecte sur la page Connexion.</p>
    </div>
@endif

@if(empty($clientUser))
    <div class="data-table-container mb-4">
        <div class="list-header">
            <h3 class="list-title mb-0">Compte portail client</h3>
        </div>
        <div class="list-body">
            <p class="text-muted mb-3">Aucun compte de connexion n'est associé à ce client.</p>
            <form method="POST" action="{{ route('dg.clients.account.create', $client) }}" class="grid grid-cols-1 gap-4 md:grid-cols-2 max-w-xl">
                @csrf
                <div>
                    <label class="form-label">Mot de passe (optionnel)</label>
                    <input type="password" name="password" class="form-input" placeholder="Laisser vide = généré automatiquement">
                </div>
                <div>
                    <label class="form-label">Confirmer</label>
                    <input type="password" name="password_confirmation" class="form-input">
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-user-plus me-1"></i> Créer le compte client
                    </button>
                </div>
            </form>
        </div>
    </div>
@else
    <div class="data-table-container mb-4">
        <div class="list-header">
            <h3 class="list-title mb-0">Compte portail client</h3>
        </div>
        <div class="list-body">
            <p class="mb-1"><strong>Email de connexion :</strong> {{ $clientUser->email }}</p>
            <p class="mb-0 text-muted text-sm">Le client peut accéder à son espace via la page Connexion.</p>
        </div>
    </div>
@endif

@include('shared.clients.show-content', [
    'client' => $client,
    'showEditButton' => false,
    'editRoute' => route('dg.clients.edit', $client),
    'souscriptionAction' => fn ($souscription) => route('dg.souscriptions.paiements', $souscription),
    'souscriptionActionLabel' => 'Voir les paiements',
])
@endsection

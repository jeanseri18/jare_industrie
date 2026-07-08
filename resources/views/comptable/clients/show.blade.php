@extends('layouts.comptable')

@section('title', 'Détails du Client')

@section('content')
<x-page-header :title="$client->nom_prenom" subtitle="Détails du client">
    <x-slot:actions>
        <a href="{{ route('comptable.clients.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
        </a>
    </x-slot:actions>
</x-page-header>

<x-alert />

@include('shared.clients.show-content', [
    'client' => $client,
    'showEditButton' => false,
    'editRoute' => null,
    'souscriptionAction' => fn ($souscription) => route('comptable.paiements.souscription', $souscription),
    'souscriptionActionLabel' => 'Gérer les paiements',
])
@endsection

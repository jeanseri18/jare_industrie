@extends(request()->routeIs('comptable.*') ? 'layouts.comptable' : 'layouts.dg')

@section('content')
@include('shared.comptabilite.frais-dossier-content', [
    'fraisDossier' => $fraisDossier,
    'indexRoute' => request()->routeIs('comptable.*') ? 'comptable.frais-dossier' : 'dg.frais-dossier',
    'payRoute' => request()->routeIs('comptable.*') ? 'comptable.frais-dossier.payer' : 'dg.frais-dossier.payer',
    'paiementsRoute' => request()->routeIs('comptable.*') ? 'comptable.paiements.souscription' : 'dg.souscriptions.paiements',
])
@endsection

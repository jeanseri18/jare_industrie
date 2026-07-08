@extends(request()->routeIs('comptable.*') ? 'layouts.comptable' : 'layouts.dg')

@section('title', 'Apports initiaux')

@section('content')
@include('shared.comptabilite.apports-initiaux-content', [
    'apportsInitiaux' => $apportsInitiaux,
    'indexRoute' => request()->routeIs('comptable.*') ? 'comptable.apports-initiaux' : 'dg.apports-initiaux',
    'payRoute' => request()->routeIs('comptable.*') ? 'comptable.apports-initiaux.payer' : 'dg.apports-initiaux.payer',
    'paiementsRoute' => request()->routeIs('comptable.*') ? 'comptable.paiements.souscription' : 'dg.souscriptions.paiements',
])
@endsection

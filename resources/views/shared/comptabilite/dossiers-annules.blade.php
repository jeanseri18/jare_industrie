@extends(request()->routeIs('comptable.*') ? 'layouts.comptable' : 'layouts.dg')

@section('title', 'Dossiers annulés')

@section('content')
@include('shared.comptabilite.dossiers-annules-content', [
    'dossiersAnnules' => $dossiersAnnules,
    'indexRoute' => request()->routeIs('comptable.*') ? 'comptable.dossiers-annules' : 'dg.dossiers-annules',
    'rembourserRoute' => request()->routeIs('comptable.*') ? 'comptable.dossiers-annules.rembourser' : 'dg.dossiers-annules.rembourser',
])
@endsection

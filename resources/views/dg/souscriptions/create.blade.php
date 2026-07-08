@extends('layouts.dg')

@section('title', 'Nouvelle souscription')

@section('content')
@include('dg.souscriptions._create-wizard', [
    'projets' => $projets,
    'biensImmobiliers' => $biensImmobiliers,
    'mutuelles' => $mutuelles,
])
@endsection

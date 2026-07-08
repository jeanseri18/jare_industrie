@extends('layouts.operateur')

@section('title', 'Nouvelle souscription')

@section('content')
@include('dg.souscriptions._create-wizard', [
    'embedded' => true,
    'storeAction' => route('operateur.souscriptions.store'),
    'dashboardUrl' => route('operateur.dashboard'),
])
@endsection

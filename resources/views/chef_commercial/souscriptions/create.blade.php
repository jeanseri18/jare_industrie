@extends('layouts.chef_commercial')

@section('title', 'Nouvelle souscription')

@section('content')
@include('dg.souscriptions._create-wizard', [
    'embedded' => true,
    'storeAction' => route('chef_commercial.souscriptions.store'),
    'dashboardUrl' => route('chef_commercial.dashboard'),
])
@endsection

@extends('layouts.app')

@section('title', 'Identité visuelle')

@section('content')
<div class="max-w-4xl mx-auto">
    <x-page-header title="Identité visuelle" subtitle="Personnalisez logo, couleurs et mentions légales pour vos documents PDF.">
        <x-slot:actions>
            <a href="{{ route('dg.parametres.identite.preview') }}" target="_blank" class="btn-secondary">Aperçu PDF</a>
        </x-slot:actions>
    </x-page-header>

    <x-alert />

    <form action="{{ route('dg.parametres.identite.update') }}" method="POST" enctype="multipart/form-data" class="card space-y-6">
        @csrf
        @method('PUT')

        @include('shared.branding.form-fields', ['branding' => $branding])

        <div class="flex justify-end gap-3">
            <a href="{{ route('dg.parametres.identite.preview') }}" target="_blank" class="btn-secondary">Aperçu PDF</a>
            <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
    </form>
</div>
@endsection

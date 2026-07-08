@extends('layouts.guest')

@section('content')
<x-auth-split max-width="max-w-3xl">
    <div class="mb-6">
        <div class="text-2xl font-bold tracking-tight">
            <span class="text-black">DIGIT</span><span class="text-[#ff7200]"> BTP</span>
        </div>
        <h1 class="mt-6 text-3xl font-bold text-slate-900">Identité visuelle</h1>
        <p class="mt-2 text-sm text-slate-500">Étape 3 — Personnalisez vos documents PDF (logo, couleurs, mentions légales)</p>
    </div>

    @include('auth.register._progress', ['step' => 3])
    <x-alert />

    <form method="POST" action="{{ route('register.complete') }}" enctype="multipart/form-data" class="card space-y-6">
        @csrf

        @include('shared.branding.form-fields', ['defaults' => $defaults])

        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
            <p class="font-medium text-slate-800">Aperçu documents</p>
            <p class="mt-1">Vos fiches de souscription, reçus et contrats afficheront automatiquement le logo et les couleurs définis ici. Vous pourrez les modifier plus tard dans <strong>Paramètres → Identité visuelle</strong>.</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('register.entreprise') }}" class="btn-secondary px-6 py-3">Retour</a>
            <button type="submit" class="btn-primary px-6 py-3">Créer mon espace</button>
        </div>
    </form>
</x-auth-split>
@endsection

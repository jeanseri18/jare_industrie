@extends('layouts.guest')

@section('content')
<x-auth-split max-width="max-w-xl">
    <div class="mb-6">
        @php $account = session('onboarding.account', []); @endphp
        <div class="text-2xl font-bold tracking-tight">
            <span class="text-black">DIGIT</span><span class="text-[#ff7200]"> BTP</span>
        </div>
        <h1 class="mt-6 text-3xl font-bold text-slate-900">Créer votre espace</h1>
        <p class="mt-2 text-sm text-slate-500">Étape 1 — Compte administrateur (Directeur général)</p>
    </div>

    @include('auth.register._progress', ['step' => 1])
    <x-alert />

    <form method="POST" action="{{ route('register.account') }}" class="grid grid-cols-1 gap-5 md:grid-cols-2">
        @csrf
        <div>
            <label class="form-label">Nom</label>
            <input name="nom" class="form-input" required value="{{ old('nom', $account['nom'] ?? '') }}">
        </div>
        <div>
            <label class="form-label">Prénom</label>
            <input name="prenom" class="form-input" required value="{{ old('prenom', $account['prenom'] ?? '') }}">
        </div>
        <div>
            <label class="form-label">Téléphone</label>
            <input name="telephone" class="form-input" required value="{{ old('telephone', $account['telephone'] ?? '') }}">
        </div>
        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" required value="{{ old('email', $account['email'] ?? '') }}">
        </div>
        <div>
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-input" required>
        </div>
        <div>
            <label class="form-label">Confirmer</label>
            <input type="password" name="password_confirmation" class="form-input" required>
        </div>
        <div class="md:col-span-2 flex flex-wrap gap-3 pt-2">
            <button type="submit" class="btn-primary px-6 py-3">Continuer</button>
            <a href="{{ route('login') }}" class="btn-secondary px-6 py-3">Déjà inscrit</a>
        </div>
    </form>

    <p class="mt-6 text-sm text-slate-500">
        Membre d'une équipe existante ?
        <a href="{{ route('register.equipe') }}" class="font-semibold text-[#ff7200] hover:underline">Rejoindre une entreprise</a>
    </p>
</x-auth-split>
@endsection

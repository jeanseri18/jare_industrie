@extends('layouts.guest')

@section('content')
<x-auth-split max-width="max-w-xl">
    <div class="mb-8">
        <div class="text-2xl font-bold tracking-tight">
            <span class="text-black">DIGIT</span><span class="text-[#ff7200]"> BTP</span>
        </div>
        <h1 class="mt-6 text-3xl font-bold text-slate-900">Rejoindre une entreprise</h1>
        <p class="mt-2 text-sm text-slate-500">Inscription d'un membre d'équipe (compte validé par le DG)</p>
    </div>

    <x-alert />

    <form method="POST" action="{{ route('register.equipe.store') }}" class="grid grid-cols-1 gap-5 md:grid-cols-2">
        @csrf
        <div class="md:col-span-2">
            <label class="form-label">Rôle</label>
            <select name="role" class="form-select" required>
                <option value="operateur">Opérateur</option>
                <option value="comptable">Comptable</option>
                <option value="chef_commercial">Chef commercial</option>
                <option value="admin_technique">Admin technique</option>
                <option value="dg">Directeur général</option>
            </select>
        </div>
        <div>
            <label class="form-label">Nom</label>
            <input name="nom" class="form-input" required value="{{ old('nom') }}">
        </div>
        <div>
            <label class="form-label">Prénom</label>
            <input name="prenom" class="form-input" required value="{{ old('prenom') }}">
        </div>
        <div>
            <label class="form-label">Téléphone</label>
            <input name="telephone" class="form-input" required value="{{ old('telephone') }}">
        </div>
        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" required value="{{ old('email') }}">
        </div>
        <div class="md:col-span-2">
            <label class="form-label">Adresse</label>
            <input name="adresse" class="form-input" required value="{{ old('adresse') }}">
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
            <button type="submit" class="btn-primary px-6 py-3">Créer mon compte</button>
            <a href="{{ route('login') }}" class="btn-secondary px-6 py-3">Déjà inscrit</a>
        </div>
    </form>

    <p class="mt-6 text-sm text-slate-500">
        Vous créez votre propre entreprise ?
        <a href="{{ route('register') }}" class="font-semibold text-[#ff7200] hover:underline">Inscription entreprise</a>
    </p>
</x-auth-split>
@endsection

@extends('layouts.guest')

@section('content')
<x-auth-split>
    <div class="mb-8">
        <div class="text-2xl font-bold tracking-tight">
            <span class="text-black">DIGIT</span><span class="text-[#ff7200]"> BTP</span>
        </div>
        <h1 class="mt-6 text-3xl font-bold text-slate-900">Connexion</h1>
        <p class="mt-2 text-sm text-slate-500">ERP immobilier & BTP en marque blanche</p>
    </div>

    <x-alert />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus>
        </div>
        <div>
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-input" required>
        </div>
        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="remember" class="rounded border-slate-300">
            Se souvenir de moi
        </label>
        <button type="submit" class="btn-primary w-full py-3">Se connecter</button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Pas encore de compte ?
        <a href="{{ route('register') }}" class="font-semibold text-[#ff7200] hover:underline">Créer mon entreprise</a>
        <span class="mx-1">·</span>
        <a href="{{ route('register.equipe') }}" class="font-semibold text-slate-600 hover:underline">Rejoindre une équipe</a>
    </p>
</x-auth-split>
@endsection

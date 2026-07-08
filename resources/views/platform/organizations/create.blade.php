@extends('layouts.platform')

@section('title', 'Nouvelle organisation')

@section('content')
<x-page-header title="Créer une organisation" subtitle="Onboarding manuel d'une entreprise BTP." />

<x-alert />

<form action="{{ route('platform.organizations.store') }}" method="POST" class="card max-w-2xl space-y-6">
    @csrf
    <x-form-section title="Organisation">
        <div class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2"><label class="form-label">Nom</label><input name="name" class="form-input" required value="{{ old('name') }}"></div>
            <div><label class="form-label">Slug</label><input name="slug" class="form-input" required value="{{ old('slug') }}"></div>
            <div><label class="form-label">Sous-domaine</label><input name="subdomain" class="form-input" required value="{{ old('subdomain') }}"></div>
            <div><label class="form-label">Plan</label>
                <select name="plan" class="form-select">
                    <option value="starter">Starter</option>
                    <option value="pro" selected>Pro</option>
                    <option value="enterprise">Enterprise</option>
                </select>
            </div>
        </div>
    </x-form-section>
    <x-form-section title="Administrateur DG initial">
        <div class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2"><label class="form-label">Nom complet</label><input name="dg_name" class="form-input" required value="{{ old('dg_name') }}"></div>
            <div><label class="form-label">Email</label><input type="email" name="dg_email" class="form-input" required value="{{ old('dg_email') }}"></div>
            <div><label class="form-label">Mot de passe</label><input type="password" name="dg_password" class="form-input" required></div>
            <div><label class="form-label">Confirmer mot de passe</label><input type="password" name="dg_password_confirmation" class="form-input" required></div>
        </div>
    </x-form-section>
    <button type="submit" class="btn-primary">Créer l'organisation</button>
</form>
@endsection

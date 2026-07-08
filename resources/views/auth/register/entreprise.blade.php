@extends('layouts.guest')

@section('content')
<x-auth-split max-width="max-w-xl">
    <div class="mb-6">
        <div class="text-2xl font-bold tracking-tight">
            <span class="text-black">DIGIT</span><span class="text-[#ff7200]"> BTP</span>
        </div>
        <h1 class="mt-6 text-3xl font-bold text-slate-900">Votre entreprise</h1>
        <p class="mt-2 text-sm text-slate-500">Étape 2 — Informations de la société</p>
    </div>

    @include('auth.register._progress', ['step' => 2])
    <x-alert />

    <form method="POST" action="{{ route('register.entreprise.store') }}" class="space-y-5"
          x-data="{
              name: @js(old('name', $company['name'] ?? '')),
              slug: @js(old('slug', $company['slug'] ?? '')),
              subdomain: @js(old('subdomain', $company['subdomain'] ?? '')),
              slugify(v) {
                  return v.toLowerCase()
                      .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                      .replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
              },
              syncFromName() {
                  const s = this.slugify(this.name);
                  if (!this.slug || this.slug === this.slugify(this.name)) {
                      this.slug = s;
                  }
                  if (!this.subdomain || this.subdomain === this.slugify(this.name).replace(/-/g, '')) {
                      this.subdomain = s.replace(/-/g, '');
                  }
              }
          }">
        @csrf
        <div>
            <label class="form-label">Nom de l'entreprise</label>
            <input type="text" name="name" class="form-input" required x-model="name" @input="syncFromName()">
        </div>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label class="form-label">Identifiant (slug)</label>
                <input type="text" name="slug" class="form-input" required x-model="slug">
            </div>
            <div>
                <label class="form-label">Sous-domaine</label>
                <input type="text" name="subdomain" class="form-input" required x-model="subdomain">
                <p class="mt-1 text-xs text-slate-500">Ex. : mon-entreprise</p>
            </div>
        </div>
        <div>
            <label class="form-label">Adresse</label>
            <input type="text" name="address" class="form-input" required value="{{ old('address', $company['address'] ?? '') }}">
        </div>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label class="form-label">Ville</label>
                <input type="text" name="city" class="form-input" required value="{{ old('city', $company['city'] ?? '') }}">
            </div>
            <div>
                <label class="form-label">Pays</label>
                <input type="text" name="country" class="form-input" required value="{{ old('country', $company['country'] ?? 'Côte d\'Ivoire') }}">
            </div>
        </div>
        <div>
            <label class="form-label">Téléphone entreprise</label>
            <input type="text" name="phone" class="form-input" required value="{{ old('phone', $company['phone'] ?? '') }}">
        </div>
        <div class="flex flex-wrap gap-3 pt-2">
            <a href="{{ route('register') }}" class="btn-secondary px-6 py-3">Retour</a>
            <button type="submit" class="btn-primary px-6 py-3">Continuer</button>
        </div>
    </form>
</x-auth-split>
@endsection

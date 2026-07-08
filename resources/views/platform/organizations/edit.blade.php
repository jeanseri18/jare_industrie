@extends('layouts.platform')

@section('title', 'Modifier organisation')

@section('content')
<x-page-header title="Modifier {{ $organization->name }}" />

<x-alert />

<form action="{{ route('platform.organizations.update', $organization) }}" method="POST" class="card max-w-2xl space-y-4">
    @csrf
    @method('PUT')
    <div><label class="form-label">Nom</label><input name="name" class="form-input" value="{{ old('name', $organization->name) }}" required></div>
    <div><label class="form-label">Slug</label><input name="slug" class="form-input" value="{{ old('slug', $organization->slug) }}" required></div>
    <div><label class="form-label">Sous-domaine</label><input name="subdomain" class="form-input" value="{{ old('subdomain', $organization->subdomain) }}" required></div>
    <div><label class="form-label">Plan</label>
        <select name="plan" class="form-select">
            @foreach(['starter','pro','enterprise'] as $p)
                <option value="{{ $p }}" @selected(old('plan', $organization->plan) === $p)>{{ ucfirst($p) }}</option>
            @endforeach
        </select>
    </div>
    <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $organization->is_active))> Organisation active</label>
    <button type="submit" class="btn-primary">Enregistrer</button>
</form>
@endsection

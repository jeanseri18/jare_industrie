@props(['branding' => null, 'defaults' => []])

@php
    $value = fn (string $key, $fallback = '') => old($key, $branding?->{$key} ?? ($defaults[$key] ?? $fallback));
@endphp

<x-form-section title="Logo et images">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Logo principal</label>
            @if($branding?->logoUrl())
                <img src="{{ $branding->logoUrl() }}" alt="Logo" class="h-16 mb-2 object-contain">
            @endif
            <input type="file" name="logo" accept="image/*" class="form-input">
        </div>
        <div>
            <label class="form-label">Filigrane PDF</label>
            <input type="file" name="watermark" accept="image/*" class="form-input">
            <p class="mt-1 text-xs text-slate-500">Arrière-plan discret sur vos documents</p>
        </div>
        <div>
            <label class="form-label">Signature DG</label>
            <input type="file" name="signature" accept="image/*" class="form-input">
        </div>
    </div>
</x-form-section>

<x-form-section title="Couleurs">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Couleur primaire</label>
            <input type="color" name="primary_color" value="{{ $value('primary_color', '#ff7200') }}" class="w-full h-10">
        </div>
        <div>
            <label class="form-label">Couleur secondaire</label>
            <input type="color" name="secondary_color" value="{{ $value('secondary_color', '#1e3a8a') }}" class="w-full h-10">
        </div>
        <div>
            <label class="form-label">Couleur accent</label>
            <input type="color" name="accent_color" value="{{ $value('accent_color', '#4CAF50') }}" class="w-full h-10">
        </div>
    </div>
</x-form-section>

<x-form-section title="Identité entreprise">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
            <label class="form-label">Raison sociale (documents PDF)</label>
            <input type="text" name="legal_name" value="{{ $value('legal_name') }}" class="form-input" required>
        </div>
        <div class="md:col-span-2">
            <label class="form-label">Slogan / tagline</label>
            <input type="text" name="tagline" value="{{ $value('tagline', 'PROMOTEUR IMMOBILIER AGRÉÉ') }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Directeur / signataire</label>
            <input type="text" name="director_name" value="{{ $value('director_name') }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Titre du signataire</label>
            <input type="text" name="director_title" value="{{ $value('director_title', 'Directeur Général') }}" class="form-input">
        </div>
    </div>
</x-form-section>

<x-form-section title="Mentions légales (documents)">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><label class="form-label">RCCM</label><input type="text" name="rccm" value="{{ $value('rccm') }}" class="form-input"></div>
        <div><label class="form-label">CC</label><input type="text" name="cc" value="{{ $value('cc') }}" class="form-input"></div>
        <div><label class="form-label">Email entreprise</label><input type="email" name="email" value="{{ $value('email') }}" class="form-input"></div>
        <div><label class="form-label">Téléphone</label><input type="text" name="phone" value="{{ $value('phone') }}" class="form-input"></div>
        <div><label class="form-label">WhatsApp</label><input type="text" name="whatsapp" value="{{ $value('whatsapp') }}" class="form-input"></div>
        <div><label class="form-label">Site web</label><input type="text" name="website" value="{{ $value('website') }}" class="form-input"></div>
        <div class="md:col-span-2">
            <label class="form-label">Pied de page personnalisé</label>
            <textarea name="footer_text" class="form-input" rows="3">{{ $value('footer_text') }}</textarea>
        </div>
    </div>
</x-form-section>

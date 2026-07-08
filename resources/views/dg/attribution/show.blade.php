@extends('layouts.dg')

@section('title', isset($souscription->attributionLot) ? 'Voir l\'attribution' : 'Attribuer le logement')

@section('content')
@php
    $attrib = $souscription->attributionLot;
    $estAttribue = !is_null($attrib);
    $inventaireUtilisable = $lotsDisponibles->isNotEmpty();
    $pageTitle = $estAttribue ? "Voir l'attribution" : 'Attribuer le logement';
@endphp

<x-page-header :title="$pageTitle" :subtitle="'Souscription ' . ($souscription->ref_souscription ?? '')">
    <x-slot:actions>
        <a href="{{ route('dg.attribution.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
        </a>
    </x-slot:actions>
</x-page-header>

<x-alert />

@if($estAttribue)
    <div class="app-alert mb-4 border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
        Cette souscription a déjà une attribution enregistrée. Les informations sont affichées ci-dessous.
    </div>
@elseif(!$estAttribue && !$inventaireUtilisable && $souscription->projet)
    <div class="app-alert mb-4 border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
        Aucun lot libre dans l'inventaire pour ce projet et ce profil (type de logement / bien).
        <a href="{{ route('dg.projets.lots.index', $souscription->projet) }}" class="underline">Gérer les îlots & lots du projet</a>
        ou complétez la saisie manuelle ci-dessous.
    </div>
@endif

<x-detail-section :title="$pageTitle" icon="fas fa-key" class="mb-0">
    <form action="{{ route('dg.souscriptions.attribuer', $souscription) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Projet</label>
                        <input type="text" class="form-control" value="{{ optional($souscription->projet)->nom }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Type de logement</label>
                        <input type="text" class="form-control" value="{{ $souscription->type_logement }}" readonly>
                    </div>

                    @if(!$estAttribue && $inventaireUtilisable)
                        <div class="col-12">
                            <label class="form-label">Choisir le lot (inventaire) <span class="text-danger">*</span></label>
                            <select name="projet_lot_id" id="select_projet_lot_id" class="form-select @error('projet_lot_id') is-invalid @enderror" required
                                data-default-superficie="{{ old('superficie') !== null && old('superficie') !== '' ? number_format((float) old('superficie'), 2, '.', '') : '' }}"
                                data-default-surface-batie="{{ old('surface_batie') !== null && old('surface_batie') !== '' ? number_format((float) old('surface_batie'), 2, '.', '') : '' }}">
                                <option value="">— Sélectionner —</option>
                                @foreach($lotsDisponibles as $pl)
                                    @php
                                        $refSurfaceBatie = $pl->bienImmobilier?->surface_habitable ?? $souscription->bienImmobilier?->surface_habitable;
                                    @endphp
                                    <option value="{{ $pl->id }}"
                                        @selected(old('projet_lot_id') == $pl->id)
                                        data-ilot="{{ e($pl->ilot) }}"
                                        data-lot="{{ e($pl->lot) }}"
                                        data-numero-villa="{{ e($pl->lot) }}"
                                        data-superficie-ref="{{ $pl->superficie !== null ? number_format((float) $pl->superficie, 2, '.', '') : '' }}"
                                        data-surface-batie-ref="{{ $refSurfaceBatie !== null ? number_format((float) $refSurfaceBatie, 2, '.', '') : '' }}"
                                        data-numero-page-guide-ref="{{ $pl->numero_page_guide !== null && $pl->numero_page_guide !== '' ? e($pl->numero_page_guide) : '' }}">
                                        {{ $pl->libelleSelect() }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">
                                La <strong>superficie</strong> et la <strong>surface bâtie</strong> sont préremplies depuis l’inventaire (superficie du lot) et le bien (surface habitable du bien lié au lot ou à la souscription) ; vous pouvez les corriger si besoin.
                            </small>
                            @error('projet_lot_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">N° de la page du guide <span class="text-danger">*</span></label>
                            <input type="text" name="numero_page_guide" id="input_numero_page_guide" maxlength="50"
                                class="form-control @error('numero_page_guide') is-invalid @enderror"
                                value="{{ old('numero_page_guide', optional($attrib)->numero_page_guide) }}"
                                required placeholder="Saisir le numéro de page (réf. inventaire proposée au changement de lot)">
                            @error('numero_page_guide')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Lot / Îlot / Villa</label>
                            <input type="text" id="input_lot_ilot_villa_resume" class="form-control bg-light" value=""
                                readonly tabindex="-1" placeholder="Choisissez un lot ci-dessus"
                                aria-live="polite" title="Aperçu issu de l’inventaire (enregistré à la validation)">
                        </div>
                    @else
                        <div class="col-md-4">
                            <label class="form-label">N° de la page du guide</label>
                            <input type="text" name="numero_page_guide" class="form-control" value="{{ old('numero_page_guide', optional($attrib)->numero_page_guide) }}" {{ $estAttribue ? 'readonly' : '' }} @if(!$estAttribue && !$inventaireUtilisable) required @endif>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Lot</label>
                            <input type="text" name="lot" class="form-control" value="{{ old('lot', optional($attrib)->lot) }}" {{ $estAttribue ? 'readonly' : '' }} @if(!$estAttribue && !$inventaireUtilisable) required @endif>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Îlot</label>
                            <input type="text" name="ilot" class="form-control" value="{{ old('ilot', optional($attrib)->ilot) }}" {{ $estAttribue ? 'readonly' : '' }} @if(!$estAttribue && !$inventaireUtilisable) required @endif>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Numéro villa</label>
                            <input type="text" name="numero_villa" class="form-control" value="{{ old('numero_villa', optional($attrib)->numero_villa) }}" {{ $estAttribue ? 'readonly' : '' }} @if(!$estAttribue && !$inventaireUtilisable) required @endif>
                        </div>
                    @endif

                    <div class="col-md-4">
                        <label class="form-label">Superficie (m²) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="superficie" id="superficie_attribution" class="form-control @error('superficie') is-invalid @enderror"
                            value="{{ old('superficie', optional($attrib)->superficie) }}"
                            {{ $estAttribue ? 'readonly' : '' }}
                            @if(!$estAttribue) required min="0" placeholder="Réf. inventaire ou saisie manuelle" @endif>
                        @error('superficie')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Surface bâtie (m²) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="surface_batie" id="surface_batie_attribution" class="form-control @error('surface_batie') is-invalid @enderror"
                            value="{{ old('surface_batie', optional($attrib)->surface_batie) }}"
                            {{ $estAttribue ? 'readonly' : '' }}
                            @if(!$estAttribue) required min="0" placeholder="Réf. bien ou saisie manuelle" @endif>
                        @error('surface_batie')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Observations internes</label>
                        <textarea name="observations_internes" class="form-control" rows="4" placeholder="Saisir vos observations" {{ $estAttribue ? 'readonly' : '' }}>{{ old('observations_internes', optional($attrib)->observations_internes) }}</textarea>
                    </div>
                </div>

        <div class="d-flex justify-content-end mt-4 gap-2">
            <a href="{{ route('dg.attribution.index') }}" class="btn-secondary">Retour</a>
            @unless($estAttribue)
                <button type="submit" class="btn-primary">Valider l'attribution</button>
            @endunless
        </div>
    </form>
</x-detail-section>
@if(!$estAttribue && $inventaireUtilisable)
@push('scripts')
<script>
(function () {
    var sel = document.getElementById('select_projet_lot_id');
    var inp = document.getElementById('superficie_attribution');
    var inpBatie = document.getElementById('surface_batie_attribution');
    var inpPage = document.getElementById('input_numero_page_guide');
    var inpResume = document.getElementById('input_lot_ilot_villa_resume');
    if (!sel || !inp || inp.readOnly) return;

    function applyLotIlotVillaFromOption(opt) {
        if (!inpResume) return;
        if (!opt || !opt.value) {
            inpResume.value = '';
            return;
        }
        var ilot = opt.getAttribute('data-ilot') || '';
        var lot = opt.getAttribute('data-lot') || '';
        var villa = opt.getAttribute('data-numero-villa') || lot;
        var parts = [];
        if (ilot !== '') parts.push('Îlot ' + ilot);
        if (lot !== '') parts.push('Lot ' + lot);
        if (villa !== '') parts.push('Villa n° ' + villa);
        inpResume.value = parts.join(' · ');
    }

    function applyRefFromOption(opt) {
        if (!opt || !opt.value) return;
        var ref = opt.getAttribute('data-superficie-ref');
        if (ref !== null && ref !== '') {
            inp.value = ref;
        } else {
            inp.value = '';
        }
    }

    function applySurfaceBatieFromOption(opt) {
        if (!inpBatie || inpBatie.readOnly) return;
        if (!opt || !opt.value) return;
        var ref = opt.getAttribute('data-surface-batie-ref');
        if (ref !== null && ref !== '') {
            inpBatie.value = ref;
        } else {
            inpBatie.value = '';
        }
    }

    function applyPageRefFromOption(opt) {
        if (!inpPage || inpPage.readOnly) return;
        if (!opt || !opt.value) return;
        var ref = opt.getAttribute('data-numero-page-guide-ref');
        if (ref !== null && ref !== '') {
            inpPage.value = ref;
        } else {
            inpPage.value = '';
        }
    }

    sel.addEventListener('change', function () {
        var opt = this.options[this.selectedIndex];
        applyRefFromOption(opt);
        applySurfaceBatieFromOption(opt);
        applyPageRefFromOption(opt);
        applyLotIlotVillaFromOption(opt);
    });

    // Au chargement : si validation en erreur, garder old('superficie') / old('surface_batie') ; sinon préremplir depuis l’option sélectionnée
    var def = sel.getAttribute('data-default-superficie');
    if (def !== null && def !== '') {
        inp.value = def;
    } else if (sel.selectedIndex > 0) {
        applyRefFromOption(sel.options[sel.selectedIndex]);
    }

    var defBatie = sel.getAttribute('data-default-surface-batie');
    if (inpBatie && !inpBatie.readOnly) {
        if (defBatie !== null && defBatie !== '') {
            inpBatie.value = defBatie;
        } else if (sel.selectedIndex > 0) {
            applySurfaceBatieFromOption(sel.options[sel.selectedIndex]);
        }
    }

    // Page du guide : préremplissage depuis l’inventaire seulement si le champ est encore vide (ex. garde old() après erreur)
    if (inpPage && !inpPage.readOnly) {
        if (!String(inpPage.value || '').trim() && sel.selectedIndex > 0) {
            applyPageRefFromOption(sel.options[sel.selectedIndex]);
        }
    }

    if (sel.selectedIndex > 0) {
        applyLotIlotVillaFromOption(sel.options[sel.selectedIndex]);
    }
})();
</script>
@endpush
@endif
@endsection

@extends('layouts.dg')

@section('title', 'Lots — Îlot '.$ilot.' — '.$projet->nom)

@section('content')
<x-page-header :title="'Lots de l’îlot ' . $ilot . ' — ' . $projet->nom">
    <x-slot:actions>
        <a href="{{ route('dg.projets.lots.index', $projet) }}" class="btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour à l’inventaire
        </a>
    </x-slot:actions>
</x-page-header>
<x-alert />

@php
            $nbLots = $lots->count();
            $sommeLots = $lots->sum(fn ($l) => (float) ($l->superficie ?? 0));
            $refTotale = $projetIlot?->superficie_totale;
            $refFloat = $refTotale !== null ? (float) $refTotale : null;
            $depasseRef = $refFloat !== null && $sommeLots > $refFloat + 0.01;
            $maxPourNouveauLot = ($refFloat !== null) ? max(0, round($refFloat - $sommeLots, 2)) : null;
        @endphp
<div class="data-table-container mb-4">
    <div class="list-body">
        <div class="alert alert-light border mb-0">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="small text-muted">Superficie totale de l’îlot (référence saisie)</div>
                    <div class="fs-5 fw-semibold text-primary">
                        @if($refTotale !== null)
                            {{ number_format((float) $refTotale, 2, ',', ' ') }} m²
                        @else
                            <span class="text-muted">—</span>
                            <span class="small d-block text-muted mt-1">Sans référence, aucune limite sur la somme des lots.</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Somme des superficies des lots (saisie par lot)</div>
                    <div class="fs-5 fw-semibold @if($depasseRef) text-danger @elseif($refFloat !== null && abs($sommeLots - $refFloat) <= 0.02) text-success @endif">
                        {{ number_format($sommeLots, 2, ',', ' ') }} m²
                        @if($refFloat !== null)
                            @if($depasseRef)
                                <span class="small d-block text-danger">Dépasse la référence îlot : ajustez les superficies (enregistrement refusé).</span>
                            @elseif($sommeLots <= $refFloat + 0.01)
                                <span class="small d-block text-muted">Reste disponible : {{ number_format(max(0, $refFloat - $sommeLots), 2, ',', ' ') }} m²</span>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="col-12 small text-muted">
                    Lorsqu’une <strong>superficie totale d’îlot</strong> est renseignée, la <strong>somme des superficies des lots</strong> ne peut pas la dépasser. Vous pouvez appliquer la <strong>même superficie</strong> ou le <strong>même bien immobilier</strong> à plusieurs lots en les cochant. Un même N° de lot ne peut pas exister deux fois sur le projet (même sur un autre îlot).
                </div>
            </div>
        </div>
    </div>
</div>

        <div class="data-table-container mb-4">
            <div class="list-body py-3">
                <h3 class="text-base font-semibold text-slate-900 mb-3">Ajouter un lot manuellement</h3>
                <form action="{{ route('dg.projets.lots.ilot.lot.store', [$projet, $ilot]) }}" method="POST" class="row g-2 align-items-end flex-wrap">
                    @csrf
                    <div class="col-auto">
                        <label class="form-label small mb-0">N° / identifiant du lot</label>
                        <input type="text" name="lot" class="form-control form-control-sm" value="{{ old('lot') }}" required maxlength="50" placeholder="ex. 16 ou A1">
                    </div>
                    <div class="col-auto">
                        <label class="form-label small mb-0">Superficie (m²) <span class="text-muted">(optionnel)</span></label>
                        <input type="number" step="0.01" name="superficie" class="form-control form-control-sm @if(session('invalid_superficie_lot_id') === 'add' && $errors->has('superficie')) is-invalid @endif"
                            value="{{ session('invalid_superficie_lot_id') === 'add' ? old('superficie') : '' }}" min="0" @if($maxPourNouveauLot !== null) max="{{ $maxPourNouveauLot }}" title="Max. {{ number_format($maxPourNouveauLot, 2, ',', ' ') }} m² (référence îlot moins somme des autres lots)" @endif placeholder="—">
                        @if(session('invalid_superficie_lot_id') === 'add' && $errors->has('superficie'))
                            <div class="invalid-feedback d-block">{{ $errors->first('superficie') }}</div>
                        @endif
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Ajouter le lot
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($nbLots > 0)
        <div class="data-table-container mb-4">
            <div class="list-body py-3">
                <h3 class="text-base font-semibold text-slate-900 mb-3"><i class="fas fa-check-double me-1 text-primary"></i> Superficie identique sur plusieurs lots</h3>
                <form id="form-superficie-masse" action="{{ route('dg.projets.lots.ilot.superficie_masse', [$projet, $ilot]) }}" method="POST" class="row g-2 align-items-end flex-wrap">
                    @csrf
                    <div id="lot-ids-superficie"></div>
                    <div class="col-auto">
                        <label class="form-label small mb-0">Superficie à appliquer (m²)</label>
                        <input type="number" step="0.01" name="superficie_masse" class="form-control form-control-sm @error('superficie_masse') is-invalid @enderror"
                            value="{{ old('superficie_masse') }}" min="0" required placeholder="ex. 125.5">
                        @error('superficie_masse')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @if(session('invalid_superficie_bulk') && $errors->has('lot_ids'))
                            <div class="text-danger small mt-1">{{ $errors->first('lot_ids') }}</div>
                        @endif
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-down me-1"></i> Appliquer aux lots cochés
                        </button>
                    </div>
                    <div class="col-12 small text-muted">
                        Cochez un ou plusieurs lots dans le tableau, saisissez la superficie commune, puis validez.
                    </div>
                </form>
            </div>
        </div>

        <div class="data-table-container mb-4">
            <div class="list-body py-3">
                <h3 class="text-base font-semibold text-slate-900 mb-3"><i class="fas fa-building me-1 text-primary"></i> Lier un bien immobilier à plusieurs lots</h3>
                @if($biensProjet->isEmpty())
                    <p class="text-muted small mb-2 mb-0">Aucun bien immobilier n’est défini pour ce projet. Créez-en un pour pouvoir les lier aux lots.</p>
                    <a href="{{ route('dg.projets.biens.create', $projet) }}?ilot={{ urlencode($ilot) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-plus me-1"></i> Créer un bien immobilier
                    </a>
                @else
                <form id="form-bien-masse" action="{{ route('dg.projets.lots.ilot.bien_masse', [$projet, $ilot]) }}" method="POST" class="row g-2 align-items-end flex-wrap">
                    @csrf
                    <div id="lot-ids-bien"></div>
                    <div class="col-md-5 col-lg-4">
                        <label class="form-label small mb-0">Bien immobilier à associer</label>
                        <select name="bien_immobilier_id" class="form-select form-select-sm @error('bien_immobilier_id') is-invalid @enderror">
                            <option value="">— Aucun lien (retirer l’association) —</option>
                            @foreach($biensProjet as $bien)
                                <option value="{{ $bien->id }}" {{ (string) old('bien_immobilier_id') === (string) $bien->id ? 'selected' : '' }}>
                                    {{ $bien->titre }} @if($bien->type) ({{ $bien->type }}) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('bien_immobilier_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @if(session('invalid_bien_bulk') && $errors->has('lot_ids'))
                            <div class="text-danger small mt-1">{{ $errors->first('lot_ids') }}</div>
                        @endif
                    </div>
                    <div class="col-auto d-flex flex-wrap align-items-end gap-2">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-link me-1"></i> Appliquer aux lots cochés
                        </button>
                        <a href="{{ route('dg.projets.biens.create', $projet) }}?ilot={{ urlencode($ilot) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i> Créer un bien immobilier
                        </a>
                    </div>
                    <div class="col-12 small text-muted">
                        Cochez les lots concernés puis choisissez le bien à leur associer (ou « Aucun lien » pour retirer l’association).
                    </div>
                </form>
                @endif
            </div>
        </div>
        @endif

<x-data-table>
    <x-slot:head>
        <tr>
            <th style="width: 2.5rem;" class="text-center">
                @if($nbLots > 0)
                    <input type="checkbox" class="form-check-input" id="select-all-lots" title="Tout sélectionner / tout désélectionner" aria-label="Tout sélectionner">
                @endif
            </th>
            <th>N° lot</th>
            <th>Bien immobilier</th>
            <th>Superficie du lot (m²)</th>
            <th>Statut</th>
            <th class="text-end">Actions</th>
        </tr>
    </x-slot:head>
                    @forelse($lots as $lot)
                    <tr>
                        <td class="text-center">
                            @if($nbLots > 0)
                                <input type="checkbox" name="lot_ids[]" value="{{ $lot->id }}" class="form-check-input lot-checkbox" aria-label="Sélectionner le lot {{ $lot->lot }}"
                                    {{ in_array($lot->id, old('lot_ids', [])) ? 'checked' : '' }}>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $lot->lot }}</td>
                        <td>
                            @if($biensProjet->isEmpty())
                                <span class="text-muted small">—</span>
                            @else
                            <form action="{{ route('dg.projets.lots.lot.bien', [$projet, $lot]) }}" method="POST" class="d-flex flex-wrap gap-2 align-items-center">
                                @csrf
                                @method('PATCH')
                                <select name="bien_immobilier_id" class="form-select form-select-sm" style="min-width: 180px; max-width: 260px;">
                                    <option value="">—</option>
                                    @foreach($biensProjet as $bien)
                                        <option value="{{ $bien->id }}" {{ (int) ($lot->bien_immobilier_id ?? 0) === (int) $bien->id ? 'selected' : '' }}>
                                            {{ \Illuminate\Support\Str::limit($bien->titre, 40) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-secondary">OK</button>
                            </form>
                            @endif
                        </td>
                        <td>
                            @php
                                $sommeAutresPourLot = $sommeLots - (float) ($lot->superficie ?? 0);
                                $maxSuperficieLot = ($refFloat !== null) ? max(0, round($refFloat - $sommeAutresPourLot, 2)) : null;
                            @endphp
                            <form action="{{ route('dg.projets.lots.lot.superficie', [$projet, $lot]) }}" method="POST" class="d-flex flex-wrap gap-2 align-items-center">
                                @csrf
                                @method('PATCH')
                                <input type="number" step="0.01" name="superficie" class="form-control form-control-sm @if(session('invalid_superficie_lot_id') !== 'add' && (int) session('invalid_superficie_lot_id') === (int) $lot->id && $errors->has('superficie')) is-invalid @endif" style="max-width: 140px;"
                                    value="{{ session('invalid_superficie_lot_id') !== 'add' && (int) session('invalid_superficie_lot_id') === (int) $lot->id ? old('superficie', $lot->superficie) : $lot->superficie }}"
                                    placeholder="—" min="0"
                                    @if($maxSuperficieLot !== null) max="{{ $maxSuperficieLot }}" title="Max. {{ number_format($maxSuperficieLot, 2, ',', ' ') }} m² (ne pas dépasser la référence îlot)" @endif>
                                <button type="submit" class="btn btn-sm btn-outline-primary">Enregistrer</button>
                            </form>
                        </td>
                        <td>
                            @if($lot->attributionLot)
                                <span class="badge bg-secondary">Attribué</span>
                            @else
                                <span class="badge bg-success">Libre</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if(!$lot->attributionLot)
                                <x-action-dropdown align="end">
                                    <li>
                                        <form action="{{ route('dg.projets.lots.destroy', [$projet, $lot]) }}" method="POST" class="action-dropdown-form" onsubmit="return confirm('Supprimer le lot {{ $lot->lot }} de cet îlot ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </li>
                                </x-action-dropdown>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Aucun lot pour cet îlot. Utilisez le formulaire ci-dessus pour en ajouter.</td>
                    </tr>
                    @endforelse
</x-data-table>
@if($nbLots > 0)
@push('scripts')
<script>
(function() {
    var all = document.getElementById('select-all-lots');
    if (!all) return;
    all.addEventListener('change', function() {
        document.querySelectorAll('.lot-checkbox').forEach(function(cb) { cb.checked = all.checked; });
    });
    function injectLotIds(containerId) {
        var container = document.getElementById(containerId);
        if (!container) return;
        container.innerHTML = '';
        document.querySelectorAll('.lot-checkbox:checked').forEach(function(cb) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'lot_ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });
    }
    function setupBulkForm(formId, containerId) {
        var form = document.getElementById(formId);
        if (!form) return;
        form.addEventListener('submit', function(e) {
            injectLotIds(containerId);
            if (!document.querySelectorAll('.lot-checkbox:checked').length) {
                e.preventDefault();
                alert('Cochez au moins un lot dans le tableau.');
            }
        });
    }
    setupBulkForm('form-superficie-masse', 'lot-ids-superficie');
    setupBulkForm('form-bien-masse', 'lot-ids-bien');
})();
</script>
@endpush
@endif
@endsection

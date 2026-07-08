@extends('layouts.dg')

@section('title', 'Îlots & lots — ' . $projet->nom)

@section('content')
<x-page-header :title="'Îlots & lots — ' . $projet->nom">
    <x-slot:actions>
        <a href="{{ route('dg.projets.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour aux projets
        </a>
    </x-slot:actions>
</x-page-header>
<x-alert />

<div class="data-table-container list-filters-card mb-4">
    <div class="list-body list-filters-card__body">
        <h3 class="text-base font-semibold text-slate-900 mb-3">Création / génération de lots</h3>
        <p class="text-muted small mb-3">
            Indiquez le <strong>N° de lot</strong> de départ, le <strong>nombre de lots</strong> à générer, et la <strong>superficie totale de l’îlot</strong> (référence).
        </p>
        <form action="{{ route('dg.projets.lots.store', $projet) }}" method="POST">
            @csrf
            <div class="row g-3 align-items-end">
                        <div class="col-md-6 col-lg-2">
                            <label class="form-label">Îlot</label>
                            <input type="text" name="ilot" class="form-control" value="{{ old('ilot') }}" required maxlength="50">
                        </div>
                        <div class="col-md-6 col-lg-2">
                            <label class="form-label">À partir du N° lot</label>
                            <input type="number" name="lot_debut" class="form-control" value="{{ old('lot_debut', 1) }}" min="1" required title="Premier numéro de lot à générer">
                        </div>
                        <div class="col-md-6 col-lg-2">
                            <label class="form-label">Nombre de lots à générer</label>
                            <input type="number" name="nombre_lots" class="form-control" value="{{ old('nombre_lots', 1) }}" min="1" max="500" required title="Combien de lots créer à la suite">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label class="form-label">Superficie totale de l’îlot (m²)</label>
                            <input type="number" step="0.01" name="superficie_total_ilot" class="form-control" value="{{ old('superficie_total_ilot') }}" required min="0">
                        </div>
                        <div class="col-lg-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-plus me-1"></i> Créer les lots
                            </button>
                        </div>
                    </div>
                </form>
    </div>
</div>

@if(isset($totalSuperficieReference) && $totalSuperficieReference > 0)
    <p class="small text-muted mb-4">
        <strong>Total des références</strong> : {{ number_format($totalSuperficieReference, 2, ',', ' ') }} m²
    </p>
@endif

<x-data-table>
    <x-slot:head>
        <tr>
            <th>Îlot</th>
            <th>Superficie totale îlot</th>
            <th>N° début lot</th>
            <th>N° fin lot</th>
            <th>Statut</th>
            <th class="text-end">Actions</th>
        </tr>
    </x-slot:head>
                    @forelse($ilots as $ilot)
                    @php $ilotKey = 'i'.md5($projet->id.'|'.$ilot->ilot); @endphp
                    <tr>
                        <td>{{ $ilot->ilot }}</td>
                        <td>{{ number_format((float) ($ilot->superficie_total ?? 0), 2, ',', ' ') }} m²</td>
                        <td>{{ $ilot->lot_debut ?? '—' }}</td>
                        <td>{{ $ilot->lot_fin ?? '—' }}</td>
                        <td>
                            @if((int) $ilot->nb_attribues > 0)
                                <span class="badge bg-secondary">Attribué ({{ (int) $ilot->nb_attribues }}/{{ (int) $ilot->nb_lots }})</span>
                            @else
                                <span class="badge bg-success">Libre ({{ (int) $ilot->nb_lots }})</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <x-action-dropdown align="end">
                                <li>
                                    <a href="{{ route('dg.projets.lots.ilot.lots', [$projet, $ilot->ilot]) }}" class="dropdown-item">
                                        <i class="fas fa-list"></i> Voir les lots
                                    </a>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $ilotKey }}">
                                        <i class="fas fa-pen"></i> Modifier
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-dup-{{ $ilotKey }}">
                                        <i class="fas fa-copy"></i> Dupliquer
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('dg.projets.lots.ilot.destroy', [$projet, $ilot->ilot]) }}" method="POST" class="action-dropdown-form" onsubmit="return confirm('Supprimer l’îlot {{ $ilot->ilot }} et tous ses lots non attribués ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger" @disabled((int) $ilot->nb_attribues > 0)>
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </li>
                            </x-action-dropdown>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Aucun îlot dans l’inventaire. Utilisez le formulaire ci-dessus.</td>
                    </tr>
                    @endforelse
</x-data-table>

@foreach($ilots as $ilot)
            @php $ilotKey = 'i'.md5($projet->id.'|'.$ilot->ilot); @endphp
            <div class="modal fade" id="modal-edit-{{ $ilotKey }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modifier l’îlot {{ $ilot->ilot }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <form action="{{ route('dg.projets.lots.ilot.update', [$projet, $ilot->ilot]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label class="form-label">Îlot</label>
                                    <input type="text" name="ilot" class="form-control" value="{{ $ilot->ilot }}" required maxlength="50">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Superficie totale de l’îlot (m²)</label>
                                    <input type="number" step="0.01" min="0" name="superficie_total_ilot" class="form-control" value="{{ (float) $ilot->superficie_total }}" required>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">N° début lot</label>
                                        <input type="number" min="1" name="lot_debut" class="form-control" value="{{ $ilot->lot_debut ?? 1 }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">N° fin lot</label>
                                        <input type="number" min="1" name="lot_fin" class="form-control" value="{{ $ilot->lot_fin ?? 1 }}" required>
                                    </div>
                                </div>
                                @if((int) $ilot->nb_attribues > 0)
                                    <div class="text-muted small mt-2">
                                        Des lots sont attribués : seule la superficie peut être ajustée sans changer l’îlot ni la plage.
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal-dup-{{ $ilotKey }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Dupliquer l’îlot {{ $ilot->ilot }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <form action="{{ route('dg.projets.lots.ilot.duplicate', [$projet, $ilot->ilot]) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <label class="form-label">Nouvel îlot</label>
                                <input type="text" name="nouvel_ilot" class="form-control" required maxlength="50" placeholder="Nom du nouvel îlot">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-secondary"><i class="fas fa-copy me-1"></i> Dupliquer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

<x-pagination :paginator="$ilots" />
@endsection

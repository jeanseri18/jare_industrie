@extends('layouts.dg')

@section('title', isset($souscription->attributionLot) ? 'Voir l\'attribution' : 'Attribuer le logement')

@section('content')
<div class="container-fluid">
    <div class="card-header-custom mb-3">
        <div class="card-title-custom">
            <i class="fas fa-key me-2"></i>
            Décisions DG / {{ isset($souscription->attributionLot) ? "Voir l'attribution" : 'Attribuer le logement' }}
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $attrib = $souscription->attributionLot;
        $estAttribue = !is_null($attrib);
    @endphp

    @if($estAttribue)
        <div class="alert alert-info">
            Cette souscription a déjà une attribution enregistrée. Les informations sont affichées ci-dessous.
        </div>
    @endif

    <div class="card">
        <div class="card-body">
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
                    <div class="col-md-4">
                        <label class="form-label">N° de la page du guide</label>
                        <input type="text" name="numero_page_guide" class="form-control" value="{{ old('numero_page_guide', optional($attrib)->numero_page_guide) }}" {{ $estAttribue ? 'readonly' : '' }} required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Lot</label>
                        <input type="text" name="lot" class="form-control" value="{{ old('lot', optional($attrib)->lot) }}" {{ $estAttribue ? 'readonly' : '' }} required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Îlot</label>
                        <input type="text" name="ilot" class="form-control" value="{{ old('ilot', optional($attrib)->ilot) }}" {{ $estAttribue ? 'readonly' : '' }} required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Numéro villa</label>
                        <input type="text" name="numero_villa" class="form-control" value="{{ old('numero_villa', optional($attrib)->numero_villa) }}" {{ $estAttribue ? 'readonly' : '' }} required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Superficie (m²)</label>
                        <input type="number" step="0.01" name="superficie" class="form-control" value="{{ old('superficie', optional($attrib)->superficie) }}" {{ $estAttribue ? 'readonly' : '' }} required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Surface bâtie (m²)</label>
                        <input type="number" step="0.01" class="form-control" value="{{ optional($souscription->bienImmobilier)->surface_habitable }}" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Observations internes</label>
                        <textarea name="observations_internes" class="form-control" rows="4" placeholder="Saisir vos observations" {{ $estAttribue ? 'readonly' : '' }}>{{ old('observations_internes', optional($attrib)->observations_internes) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('dg.attribution.index') }}" class="btn btn-outline-secondary me-2">Retour</a>
                    @unless($estAttribue)
                        <button type="submit" class="btn btn-primary">Valider l'attribution</button>
                    @endunless
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

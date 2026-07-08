@extends('layouts.dg')

@section('title', 'Créer un Bien Immobilier')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-plus-circle me-2"></i>
            Créer un Nouveau Bien Immobilier - {{ $projet->nom }}
        </div>
        @if(request()->filled('ilot'))
            <a href="{{ route('dg.projets.lots.ilot.lots', [$projet, request('ilot')]) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour aux lots (îlot)
            </a>
        @else
            <a href="{{ route('dg.projets.biens.index', $projet) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        @endif
    </div>
    <div style="padding: 20px;">
        <form method="POST" action="{{ route('dg.projets.biens.store', $projet) }}" class="needs-validation" novalidate>
            @csrf
            @if(request()->filled('ilot'))
                <input type="hidden" name="ilot" value="{{ request('ilot') }}">
            @endif
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre du Bien *</label>
                        <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                               id="titre" name="titre" value="{{ old('titre') }}" required>
                        @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">Type de Bien *</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Sélectionner un type</option>
                            <option value="duplex" {{ old('type') == 'duplex' ? 'selected' : '' }}>Duplex</option>
                            <option value="appartement" {{ old('type') == 'appartement' ? 'selected' : '' }}>Appartement</option>
                            <option value="villa" {{ old('type') == 'villa' ? 'selected' : '' }}>Villa</option>
                            <option value="etage" {{ old('type') == 'etage' ? 'selected' : '' }}>Étage</option>
                            <option value="villa basse" {{ old('type') == 'villa basse' ? 'selected' : '' }}>Villa basse</option>
                            <option value="villa +R1" {{ old('type') == 'villa +R1' ? 'selected' : '' }}>Villa +R1</option>
                            <option value="terrain" {{ old('type') == 'terrain' ? 'selected' : '' }}>Terrain</option>
                            <option value="autre" {{ old('type') == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="prix" class="form-label">Prix *</label>
                        <input type="number" step="0.01" class="form-control @error('prix') is-invalid @enderror" 
                               id="prix" name="prix" value="{{ old('prix') }}" min="0" required>
                        @error('prix')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="frais_souscription" class="form-label">Frais de Souscription *</label>
                        <input type="number" step="0.01" class="form-control @error('frais_souscription') is-invalid @enderror" 
                               id="frais_souscription" name="frais_souscription" value="{{ old('frais_souscription') }}" min="0" required>
                        @error('frais_souscription')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="pourcentage_apport" class="form-label">Pourcentage d'Apport (%)</label>
                        <input type="number" class="form-control @error('pourcentage_apport') is-invalid @enderror" 
                               id="pourcentage_apport" name="pourcentage_apport" value="{{ old('pourcentage_apport', 30) }}" min="0" max="100" required>
                        @error('pourcentage_apport')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="apport_initial" class="form-label">Apport Initial</label>
                        <input type="number" step="0.01" class="form-control @error('apport_initial') is-invalid @enderror" 
                               id="apport_initial" name="apport_initial" value="{{ old('apport_initial') }}" min="0">
                        @error('apport_initial')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="nbre_piece" class="form-label">Nombre de Pièces</label>
                        <input type="number" class="form-control @error('nbre_piece') is-invalid @enderror" 
                               id="nbre_piece" name="nbre_piece" value="{{ old('nbre_piece') }}" min="0">
                        @error('nbre_piece')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="nbre_salon" class="form-label">Nombre de Salons</label>
                        <input type="number" class="form-control @error('nbre_salon') is-invalid @enderror" 
                               id="nbre_salon" name="nbre_salon" value="{{ old('nbre_salon') }}" min="0">
                        @error('nbre_salon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="nbre_douche" class="form-label">Nombre de Douches</label>
                        <input type="number" class="form-control @error('nbre_douche') is-invalid @enderror" 
                               id="nbre_douche" name="nbre_douche" value="{{ old('nbre_douche') }}" min="0">
                        @error('nbre_douche')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="nbre_cuisine" class="form-label">Nombre de Cuisines</label>
                        <input type="number" class="form-control @error('nbre_cuisine') is-invalid @enderror" 
                               id="nbre_cuisine" name="nbre_cuisine" value="{{ old('nbre_cuisine') }}" min="0">
                        @error('nbre_cuisine')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="surface_habitable" class="form-label">Surface Habitable (m²)</label>
                        <input type="number" step="0.01" class="form-control @error('surface_habitable') is-invalid @enderror" 
                               id="surface_habitable" name="surface_habitable" value="{{ old('surface_habitable') }}" min="0">
                        @error('surface_habitable')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="surface_total" class="form-label">Surface Total (m²)</label>
                        <input type="number" step="0.01" class="form-control @error('surface_total') is-invalid @enderror" 
                               id="surface_total" name="surface_total" value="{{ old('surface_total') }}" min="0">
                        @error('surface_total')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="nbre_salle_bain" class="form-label">Nombre de Salles de Bain</label>
                        <input type="number" class="form-control @error('nbre_salle_bain') is-invalid @enderror" 
                               id="nbre_salle_bain" name="nbre_salle_bain" value="{{ old('nbre_salle_bain') }}" min="0">
                        @error('nbre_salle_bain')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="nbre_etage" class="form-label">Nombre d'Étages</label>
                        <input type="number" class="form-control @error('nbre_etage') is-invalid @enderror" 
                               id="nbre_etage" name="nbre_etage" value="{{ old('nbre_etage') }}" min="0">
                        @error('nbre_etage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="nbre_place_garage" class="form-label">Places de Garage</label>
                        <input type="number" class="form-control @error('nbre_place_garage') is-invalid @enderror" 
                               id="nbre_place_garage" name="nbre_place_garage" value="{{ old('nbre_place_garage') }}" min="0">
                        @error('nbre_place_garage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>



            <div class="row mb-3">
                <div class="col-12">
                    <h5 class="mb-3">Commodités</h5>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="cour_avant" name="cour_avant" value="1" {{ old('cour_avant') ? 'checked' : '' }}>
                        <label class="form-check-label" for="cour_avant">Cour Avant</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="cour_arriere" name="cour_arriere" value="1" {{ old('cour_arriere') ? 'checked' : '' }}>
                        <label class="form-check-label" for="cour_arriere">Cour Arrière</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="terrasse_carrelee" name="terrasse_carrelee" value="1" {{ old('terrasse_carrelee') ? 'checked' : '' }}>
                        <label class="form-check-label" for="terrasse_carrelee">Terrasse Carrelée</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="grand_sejour_carrele" name="grand_sejour_carrele" value="1" {{ old('grand_sejour_carrele') ? 'checked' : '' }}>
                        <label class="form-check-label" for="grand_sejour_carrele">Grand Séjour Carrelé</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="chambre_principale_carrelee" name="chambre_principale_carrelee" value="1" {{ old('chambre_principale_carrelee') ? 'checked' : '' }}>
                        <label class="form-check-label" for="chambre_principale_carrelee">Chambre Principale Carrelée</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="chambre_principale_salle_eau" name="chambre_principale_salle_eau" value="1" {{ old('chambre_principale_salle_eau') ? 'checked' : '' }}>
                        <label class="form-check-label" for="chambre_principale_salle_eau">Ch. Principale avec Salle d'Eau</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="chambre_principale_placards" name="chambre_principale_placards" value="1" {{ old('chambre_principale_placards') ? 'checked' : '' }}>
                        <label class="form-check-label" for="chambre_principale_placards">Ch. Principale avec Placards</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="chambre_2_carrelee" name="chambre_2_carrelee" value="1" {{ old('chambre_2_carrelee') ? 'checked' : '' }}>
                        <label class="form-check-label" for="chambre_2_carrelee">Chambre 2 Carrelée</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="chambre_2_salle_eau" name="chambre_2_salle_eau" value="1" {{ old('chambre_2_salle_eau') ? 'checked' : '' }}>
                        <label class="form-check-label" for="chambre_2_salle_eau">Ch. 2 avec Salle d'Eau</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="chambre_2_placard" name="chambre_2_placard" value="1" {{ old('chambre_2_placard') ? 'checked' : '' }}>
                        <label class="form-check-label" for="chambre_2_placard">Ch. 2 avec Placard</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="wc_visiteur_carrele" name="wc_visiteur_carrele" value="1" {{ old('wc_visiteur_carrele') ? 'checked' : '' }}>
                        <label class="form-check-label" for="wc_visiteur_carrele">WC Visiteur Carrelé</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="grande_cuisine_carrelee" name="grande_cuisine_carrelee" value="1" {{ old('grande_cuisine_carrelee') ? 'checked' : '' }}>
                        <label class="form-check-label" for="grande_cuisine_carrelee">Grande Cuisine Carrelée</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="buanderie" name="buanderie" value="1" {{ old('buanderie') ? 'checked' : '' }}>
                        <label class="form-check-label" for="buanderie">Buanderie/Débarras</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="installation_chauffe_eau" name="installation_chauffe_eau" value="1" {{ old('installation_chauffe_eau') ? 'checked' : '' }}>
                        <label class="form-check-label" for="installation_chauffe_eau">Installation Chauffe-Eau</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="garage" name="garage" value="1" {{ old('garage') ? 'checked' : '' }}>
                        <label class="form-check-label" for="garage">Garage</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="piscine" name="piscine" value="1" {{ old('piscine') ? 'checked' : '' }}>
                        <label class="form-check-label" for="piscine">Piscine</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="terrasse" name="terrasse" value="1" {{ old('terrasse') ? 'checked' : '' }}>
                        <label class="form-check-label" for="terrasse">Terrasse</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="jardin" name="jardin" value="1" {{ old('jardin') ? 'checked' : '' }}>
                        <label class="form-check-label" for="jardin">Jardin</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="dependance" name="dependance" value="1" {{ old('dependance') ? 'checked' : '' }}>
                        <label class="form-check-label" for="dependance">Dépendance</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Créer
                </button>
                @if(request()->filled('ilot'))
                    <a href="{{ route('dg.projets.lots.ilot.lots', [$projet, request('ilot')]) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                @else
                    <a href="{{ route('dg.projets.biens.index', $projet) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const prixInput = document.getElementById('prix');
    const pourcentageApportInput = document.getElementById('pourcentage_apport');
    const apportInitialInput = document.getElementById('apport_initial');

    function calculerApportInitial() {
        const prix = parseFloat(prixInput.value) || 0;
        const pourcentage = parseFloat(pourcentageApportInput.value) || 0;
        const apportInitial = (prix * pourcentage) / 100;
        apportInitialInput.value = apportInitial.toFixed(2);
    }

    // Calculer l'apport initial au chargement de la page
    calculerApportInitial();

    // Écouter les changements sur le prix et le pourcentage
    prixInput.addEventListener('input', calculerApportInitial);
    pourcentageApportInput.addEventListener('input', calculerApportInitial);
});
</script>
@endpush

@endsection

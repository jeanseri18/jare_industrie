@extends('layouts.chef_commercial')

@section('title','Corriger une souscription - Chef Commercial')
@section('content')
@php
    $projets = $projets ?? \App\Models\Projet::where('est_actif', true)->get();
    $biensImmobiliers = $biensImmobiliers ?? [];
    foreach ($projets as $projet) {
        $biensImmobiliers[$projet->id] = \App\Models\BienImmobilier::where('idprojet', $projet->id)->get();
    }
@endphp

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { background: linear-gradient(135deg, #003d82 0%, #0056b3 100%); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; min-height: 100vh; padding: 20px; }
    .container { max-width: 900px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
    .header { background: white; padding: 30px 20px 20px; text-align: center; border-bottom: 1px solid #e0e0e0; }
    .header h2 { font-size: 20px; color: #2c3e50; margin-bottom: 5px; }
    .header p { font-size: 14px; color: #7f8c8d; }
    .badge-info { display:inline-block; background:#eef6ff; color:#1f6feb; border:1px solid #cfe2ff; border-radius:6px; padding:8px 12px; font-size:13px; margin-top:10px; }
    .progress-bar { background: #ecf0f1; height: 4px; position: relative; }
    .progress-fill { background: #2c5f8d; height: 100%; transition: width 0.3s ease; }
    .step-indicator { text-align: right; padding: 15px 30px; font-size: 14px; color: #7f8c8d; font-weight: 600; }
    .step { display: none; padding: 30px; }
    .step.active { display: block; animation: fadeIn 0.3s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .step h3 { font-size: 18px; color: #2c3e50; margin-bottom: 25px; }
    .category-selection { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 30px; }
    .category-card { border: 2px solid #e0e0e0; border-radius: 8px; padding: 20px 10px; text-align: center; cursor: pointer; transition: all 0.3s ease; background: white; }
    .category-card:hover { border-color: #2c5f8d; background: #f8f9fa; }
    .category-card.selected { border-color: #2c5f8d; background: #e8f4f8; }
    .category-icon { width: 50px; height: 50px; margin: 0 auto 10px; background: #ecf0f1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; }
    .category-card.selected .category-icon { background: #2c5f8d; color: white; }
    .category-label { font-size: 13px; color: #2c3e50; font-weight: 500; }
    .form-group { margin-bottom: 20px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    label { display: block; font-size: 14px; color: #555; margin-bottom: 8px; font-weight: 500; }
    input, select { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: border-color 0.3s ease; background: white; }
    input:focus, select:focus { outline: none; border-color: #2c5f8d; }
    .file-upload-area { border: 2px dashed #ddd; border-radius: 6px; padding: 30px; text-align: center; cursor: pointer; transition: all 0.3s ease; background: #fafafa; }
    .file-upload-area:hover { border-color: #2c5f8d; background: #f0f8ff; }
    .file-upload-icon { font-size: 40px; color: #95a5a6; margin-bottom: 10px; }
    .file-upload-text { color: #7f8c8d; font-size: 13px; }
    .checkbox-group { display: flex; gap: 20px; flex-wrap: wrap; margin-top: 10px; }
    .checkbox-item { display: flex; align-items: center; gap: 8px; }
    .housing-options, .payment-options { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 10px; }
    .option-card { border: 2px solid #e0e0e0; border-radius: 6px; padding: 15px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px; }
    .option-card:hover { border-color: #2c5f8d; background: #f8f9fa; }
    .option-card.selected { border-color: #2c5f8d; background: #e8f4f8; }
    .option-checkbox { width: 20px; height: 20px; border: 2px solid #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .option-card.selected .option-checkbox { background: #2c5f8d; border-color: #2c5f8d; color: white; }
    .option-card.selected .option-checkbox::before { content: '✓'; font-size: 14px; }
    .summary-box { background: #f8f9fa; border-left: 4px solid #2c5f8d; padding: 20px; border-radius: 6px; margin: 25px 0; }
    .summary-box h4 { color: #2c3e50; margin-bottom: 15px; font-size: 16px; }
    .summary-line { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; color: #555; }
    .warning-box { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 6px; margin: 20px 0; font-size: 13px; color: #856404; }
    .buttons { display: flex; justify-content: space-between; gap: 15px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0; }
    button { padding: 14px 30px; border: none; border-radius: 6px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; flex: 1; }
    .btn-secondary { background: #ecf0f1; color: #555; }
    .btn-secondary:hover { background: #d5dbdb; }
    .btn-primary { background: #2c5f8d; color: white; }
    .btn-primary:hover { background: #234a6e; }
    .success-screen { display: none; text-align: center; padding: 60px 30px; }
    .success-screen.active { display: block; }
    .recap-box { background: #f8f9fa; border-radius: 6px; padding: 20px; text-align: left; margin: 30px 0; }
    .recap-item { margin-bottom: 8px; font-size: 14px; color: #555; }
    .recap-item strong { color: #2c3e50; }
    @media (max-width: 600px) { .category-selection { grid-template-columns: 1fr; } .form-row { grid-template-columns: 1fr; } .housing-options, .payment-options { grid-template-columns: 1fr; } }
</style>

<div class="container">
    <div class="header">
        <div class="logo-display"><img src="{{ asset('LOGO.png') }}" alt="Logo" style="max-width: 200px; height: auto;"></div><br>
        <h2>Correction de souscription #{{ $souscription->id }}</h2>
        <p>Promoteur immobilier agréé</p>
        @if($souscription->statut === 'en_attente_correction')
            <div class="badge-info">Statut : <strong>En attente de correction</strong></div>
        @endif
    </div>

    <div class="progress-bar"><div class="progress-fill" id="progressFill" style="width: 20%"></div></div>
    <div class="step-indicator" id="stepIndicator">1/5</div>

    <form id="editSubscriptionForm" action="{{ route('chef_commercial.souscriptions.update', $souscription->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger" style="margin: 20px 30px; padding: 15px; border-radius: 6px; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24;">
                <strong>Erreurs :</strong>
                <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </div>
        @endif

        <div class="step active" id="step0">
            <h3>Choisissez la catégorie de client</h3>
            <div class="category-selection">
                <div class="category-card" data-category="Client individuel" onclick="selectCategory(this, 'Client individuel')">
                    <div class="category-icon"><i class="bi bi-person"></i></div><div class="category-label">Client individuel</div>
                </div>
                <div class="category-card" data-category="Association Syndicat Mutuelle" onclick="selectCategory(this, 'Association Syndicat Mutuelle')">
                    <div class="category-icon"><i class="bi bi-people"></i></div><div class="category-label">Association Syndicat Mutuelle</div>
                </div>
                <div class="category-card" data-category="Client diaspora" onclick="selectCategory(this, 'Client diaspora')">
                    <div class="category-icon"><i class="bi bi-globe"></i></div><div class="category-label">Client diaspora</div>
                </div>
            </div>
            @php
                $catMap = ['individuel'=>'Client individuel','association'=>'Association Syndicat Mutuelle','syndicat'=>'Association Syndicat Mutuelle','mutuelle'=>'Association Syndicat Mutuelle','diaspora'=>'Client diaspora'];
                $catVal = old('clientCategory', $catMap[$souscription->categorie_client] ?? '');
                $orgVal = old('organisation_type', match($souscription->categorie_client) {
                    'association' => 'Association',
                    'syndicat' => 'Syndicat',
                    'mutuelle' => 'Mutuelle',
                    default => ''
                });
            @endphp
            <input type="hidden" name="clientCategory" id="clientCategory" required value="{{ $catVal }}">
            <div id="organisationTypeWrapper" style="display:none; margin-top: 15px;">
                <label>Organisation</label>
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="radio" name="organisation_type" value="Association" id="org_association" {{ $orgVal == 'Association' ? 'checked' : '' }} onchange="toggleOrganisationFields()">
                        <label for="org_association">Association</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="radio" name="organisation_type" value="Syndicat" id="org_syndicat" {{ $orgVal == 'Syndicat' ? 'checked' : '' }} onchange="toggleOrganisationFields()">
                        <label for="org_syndicat">Syndicat</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="radio" name="organisation_type" value="Mutuelle" id="org_mutuelle" {{ $orgVal == 'Mutuelle' ? 'checked' : '' }} onchange="toggleOrganisationFields()">
                        <label for="org_mutuelle">Mutuelle</label>
                    </div>
                </div>
            </div>
            <div class="mutuelle-select" id="mutuelleSelectWrapper" style="display:none; margin-top: 15px;">
                <label>Mutuelle</label>
                <select name="mutuelle_id" id="mutuelleSelect" disabled>
                    <option value="">-- Sélectionnez une mutuelle --</option>
                    @isset($mutuelles)
                        @php $mutuelleVal = old('mutuelle_id', $souscription->client->mutuelle_id ?? null); @endphp
                        @foreach($mutuelles as $m)
                            <option value="{{ $m->id }}" data-project="{{ $m->project_id ?? '' }}" {{ (string)$mutuelleVal === (string)$m->id ? 'selected' : '' }}>{{ $m->nom }}</option>
                        @endforeach
                    @endisset
                </select>
                <small class="text-muted">Si la mutuelle propose un prix spécial pour le bien choisi, il sera appliqué automatiquement.</small>
            </div>
            <div class="buttons"><button type="button" class="btn-primary" onclick="nextStep()" id="continueCategory">Continuer</button></div>
        </div>

        <div class="step" id="step1">
            <h3>Informations personnelles</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" required value="{{ old('nom', $souscription->nom) }}">
                </div>
                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="prenom" required value="{{ old('prenom', $souscription->prenom) }}">
                </div>
            </div>
            <div class="form-group">
                <label>Date de naissance</label>
                <input type="date" name="birthDate" required value="{{ old('birthDate', $souscription->date_naissance ? $souscription->date_naissance->format('Y-m-d') : '') }}">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Lieu de naissance</label>
                    <input type="text" name="birthPlace" required value="{{ old('birthPlace', $souscription->lieu_naissance) }}">
                </div>
                <div class="form-group">
                    <label>Nationalité</label>
                    <input type="text" name="nationality" required value="{{ old('nationality', $souscription->nationalite) }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Nombre d'enfants</label>
                    <input type="number" name="children" min="0" required value="{{ old('children', $souscription->nombre_enfants) }}">
                </div>
                <div class="form-group">
                    <label>Ayant droit</label>
                    <input type="text" name="heirs" required value="{{ old('heirs', $souscription->ayant_droit) }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required value="{{ old('email', $souscription->email) }}">
                </div>
                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="text" name="phone" required value="{{ old('phone', $souscription->telephone) }}">
                </div>
            </div>
            <div class="form-group">
                <label>Salaire mensuel</label>
                <input type="text" name="salary" required value="{{ old('salary', $souscription->salaire_mensuel) }}" oninput="formatInputMontant(this)">
            </div>
            <div class="form-group">
                <label>Situation matrimoniale:</label>
                @php $sitVal = old('maritalStatus', $souscription->situation_matrimoniale); @endphp
                <div class="checkbox-group">
                    @foreach(['celibataire'=>'Célibataire','marie'=>'Marié(e)','concubinage'=>'Concubinage','divorce'=>'Divorcé(e)','veuf'=>'Veuf(ve)'] as $k=>$v)
                        <div class="checkbox-item">
                            <input type="radio" name="maritalStatus" value="{{ $v }}" id="sit_{{ $k }}" {{ $sitVal == $k || $sitVal == $v ? 'checked' : '' }} onchange="toggleConjoint()">
                            <label for="sit_{{ $k }}">{{ $v }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div id="conjointFields" style="display: none; background:#f8f9fa; padding:15px; border-radius:8px; margin-top:15px; border-left:4px solid #2c5f8d;">
                <div class="form-row">
                    <div class="form-group"><label>Nom conjoint</label><input type="text" name="nomConjoint" value="{{ old('nomConjoint', $souscription->nom_conjoint) }}"></div>
                    <div class="form-group"><label>Tél conjoint</label><input type="text" name="telephoneConjoint" value="{{ old('telephoneConjoint', $souscription->telephone_conjoint) }}"></div>
                </div>
            </div>
            <div class="buttons"><button type="button" class="btn-secondary" onclick="prevStep()">Retour</button><button type="button" class="btn-primary" onclick="nextStep()">Continuer</button></div>
        </div>

        <div class="step" id="step2">
            <h3>Identification et Programme</h3>
            <div class="form-group">
                <label>Nature de la pièce:</label>
                @php $pieceVal = old('idType', $souscription->nature_piece); @endphp
                <div class="checkbox-group">
                    @foreach(['cni'=>'CNI','passeport'=>'Passeport','carte_consulaire'=>'Carte consulaire'] as $k=>$v)
                        <div class="checkbox-item">
                            <input type="checkbox" name="idType" value="{{ $v }}" id="pc_{{ $k }}" {{ $pieceVal == $k || $pieceVal == $v ? 'checked' : '' }} onclick="uncheckOthers(this, 'idType')">
                            <label for="pc_{{ $k }}">{{ $v }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="form-group"><label>Numéro Pièce</label><input type="text" name="idNumber" required value="{{ old('idNumber', $souscription->numero_piece) }}"></div>
            <div class="form-group">
                <label>Fichier Pièce</label>
                @if($souscription->fichier_piece)<div class="mb-2 small text-success">Fichier existant: <a href="{{ Storage::url($souscription->fichier_piece) }}" target="_blank">Voir</a></div>@endif
                <input type="file" name="idFile" onchange="updateFileName(this)">
                <div id="fileName" class="small text-muted mt-1"></div>
            </div>
            <div class="form-group">
                <label>Programme</label>
                <select name="program" required onchange="chargerBiensImmobiliers()">
                    <option value="">-- Sélectionner --</option>
                    @foreach($projets as $p)<option value="{{ $p->id }}" {{ old('program', $souscription->programme) == $p->id ? 'selected' : '' }}>{{ $p->nom }}</option>@endforeach
                </select>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Date début</label><input type="date" name="startDate" required value="{{ old('startDate', $souscription->date_debut ? $souscription->date_debut->format('Y-m-d') : '') }}"></div>
                <div class="form-group"><label>Date fin</label><input type="date" name="endDate" required value="{{ old('endDate', $souscription->date_fin ? $souscription->date_fin->format('Y-m-d') : '') }}"></div>
            </div>
            <div class="buttons"><button type="button" class="btn-secondary" onclick="prevStep()">Retour</button><button type="button" class="btn-primary" onclick="nextStep()">Continuer</button></div>
        </div>

        <div class="step" id="step3">
            <h3>Logement et Financement</h3>
            <div class="form-group">
                <label>Type de logement:</label>
                <div id="housingOptionsContainer" class="housing-options"></div>
                <input type="hidden" name="housingType" id="housingType" required value="{{ old('housingType', $souscription->bien_immobilier_id ? $souscription->bien_immobilier_id . '|' . ($souscription->bienImmobilier->titre ?? '') : '') }}">
            </div>
            <div class="form-group">
                <label>Mode paiement:</label>
                <div class="payment-options">
                    @foreach(['ESPECES','VIREMENT','PRELEVEMENT_SOURCE','TEMPERAMENT','CREDIT_BANCAIRE'] as $m)
                        <div class="option-card" onclick="selectOption(this, 'paymentMode', '{{ $m }}')"><div class="option-checkbox"></div><span>{{ str_replace('_', ' ', $m) }}</span></div>
                    @endforeach
                </div>
                <input type="hidden" name="paymentMode" id="paymentMode" required value="{{ old('paymentMode', $souscription->mode_paiement) }}">
            </div>
            <div class="summary-box">
                <h4>💰 Valeur: <span id="valeurSouscription">-</span> FCFA</h4>
                <div class="summary-line"><span>Apport (<span id="pourcentageApport">-</span>%)</span><strong><span id="apportInitial">-</span> FCFA</strong></div>
            </div>
            <div class="warning-box">Frais: <span id="fraisSouscription">-</span> FCFA</div>
            <div class="form-group">
                <div class="checkbox-item">
                    @php $apportPayeVal = old('apport_initial_paye_par_client', $souscription->apport_initial_paye_par_client ?? true) ? '1' : '0'; @endphp
                    <input type="hidden" name="apport_initial_paye_par_client" value="0">
                    <input type="checkbox" id="apport_initial_paye_par_client" name="apport_initial_paye_par_client" value="1" {{ $apportPayeVal === '1' ? 'checked' : '' }}>
                    <label for="apport_initial_paye_par_client">Apport initial à payer par le client</label>
                </div>
            </div>
            <input type="hidden" name="valeur_souscription" id="valeur_souscription_input" value="{{ old('valeur_souscription', $souscription->prix_logement) }}">
            <input type="hidden" name="apport_initial" id="apport_initial_input" value="{{ old('apport_initial', $souscription->apport_initial) }}">
            <input type="hidden" name="frais_souscription" id="frais_souscription_input" value="{{ old('frais_souscription', $souscription->frais_souscription) }}">
            <div class="buttons"><button type="button" class="btn-secondary" onclick="prevStep()">Retour</button><button type="button" class="btn-primary" onclick="nextStep()">Continuer</button></div>
        </div>

        <div class="step" id="step4">
            <h3>Récapitulatif</h3>
            <div id="recapContent" class="recap-box"></div>
            <div class="checkbox-item"><input type="checkbox" id="certify" name="certify" required><label for="certify">Je certifie l'exactitude des infos</label></div>
            <div class="buttons"><button type="button" class="btn-secondary" onclick="prevStep()">Retour</button><button type="submit" class="btn-primary">Mettre à jour</button></div>
        </div>
    </form>
</div>

<script type="application/json" id="biensImmobiliersData">@php echo json_encode($biensImmobiliers); @endphp</script>
<script>
    let currentStep = 0;
    const biensImmobiliers = JSON.parse(document.getElementById('biensImmobiliersData')?.textContent || '{}');

    function updateProgress() {
        document.getElementById('progressFill').style.width = ((currentStep + 1) / 5) * 100 + '%';
        document.getElementById('stepIndicator').textContent = (currentStep + 1) + '/5';
    }
    function showStep(n) {
        document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
        document.getElementById('step' + n).classList.add('active');
        updateProgress();
    }
    function selectCategory(el, v) {
        document.querySelectorAll('.category-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('clientCategory').value = v;
        toggleOrganisationFields();
    }
    function selectOption(el, f, v) {
        el.parentElement.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById(f).value = v;
    }
    function uncheckOthers(c, n) {
        document.getElementsByName(n).forEach(i => { if(i!==c) i.checked=false; });
    }
    function nextStep() {
        if (validateStep(currentStep)) {
            if (currentStep === 3) generateRecap();
            currentStep++; showStep(currentStep);
        }
    }
    function prevStep() { currentStep--; showStep(currentStep); }
    function validateStep(s) {
        const step = document.getElementById('step' + s);
        const req = step.querySelectorAll('[required]');
        const processedRadioNames = new Set();
        for (let f of req) {
            const type = (f.getAttribute('type') || '').toLowerCase();
            if (type === 'radio') {
                const name = f.getAttribute('name') || '';
                if (!name || processedRadioNames.has(name)) continue;
                processedRadioNames.add(name);
                const checked = step.querySelector('input[type="radio"][name="' + name + '"]:checked');
                if (!checked) {
                    alert('Champ obligatoire');
                    f.focus();
                    return false;
                }
                continue;
            }

            if (type === 'checkbox') {
                if (!f.checked) {
                    alert('Champ obligatoire');
                    f.focus();
                    return false;
                }
                continue;
            }

            if (!String(f.value || '').trim()) {
                alert('Champ obligatoire');
                f.focus();
                return false;
            }
        }
        return true;
    }
    function formatMontant(m) { return new Intl.NumberFormat('fr-FR').format(m).replace(/\u202f/g, ' '); }
    function formatInputMontant(i) { let v = i.value.replace(/\D/g, ''); if(v) i.value = formatMontant(v); }
    function toggleConjoint() {
        const mar = document.querySelector('input[name="maritalStatus"]:checked')?.value;
        document.getElementById('conjointFields').style.display = (mar === 'Marié(e)' || mar === 'marie') ? 'block' : 'none';
    }
    function chargerBiensImmobiliers() {
        const pid = document.querySelector('select[name="program"]').value;
        const cont = document.getElementById('housingOptionsContainer');
        const hType = document.getElementById('housingType');
        cont.innerHTML = '';
        if(!pid || !biensImmobiliers[pid]) return;
        const mutSel = document.getElementById('mutuelleSelect');
        if (mutSel) {
            const opts = mutSel.querySelectorAll('option');
            opts.forEach(o => {
                const proj = o.getAttribute('data-project');
                if (!o.value) return;
                if (proj && String(proj) !== String(pid)) {
                    o.style.display = 'none';
                    if (mutSel.value === o.value) mutSel.value = '';
                } else {
                    o.style.display = '';
                }
            });
        }
        biensImmobiliers[pid].forEach(b => {
            const card = document.createElement('div');
            card.className = 'option-card';
            if(hType.value.startsWith(b.id + '|')) card.classList.add('selected');
            card.onclick = function() {
                selectOption(this, 'housingType', b.id + '|' + b.titre);
                calculer(b);
            };
            card.innerHTML = `<div class="option-checkbox"></div><div><strong>${b.titre}</strong><br><small>${formatMontant(b.prix)} FCFA</small></div>`;
            cont.appendChild(card);
            if(card.classList.contains('selected')) calculer(b);
        });
    }
    function calculer(b) {
        let valeurSouscription = parseFloat(b.prix) || 0;
        const mutuelleSel = document.getElementById('mutuelleSelect');
        const orgType = (document.querySelector('input[name="organisation_type"]:checked')?.value || '').toLowerCase();
        if (orgType === 'mutuelle' && mutuelleSel && mutuelleSel.value && Array.isArray(b.mutuelles)) {
            const m = b.mutuelles.find(x => String(x.id) === String(mutuelleSel.value));
            if (m && m.pivot && m.pivot.prix_special) {
                valeurSouscription = parseFloat(m.pivot.prix_special);
            }
        }
        const pct = parseFloat(b.pourcentage_apport) || 10;
        const appCalc = parseFloat(b.apport_initial) || Math.round(valeurSouscription * (pct/100));
        const fr = parseFloat(b.frais_souscription) || 500000;
        const apportPaye = document.getElementById('apport_initial_paye_par_client')?.checked ?? true;
        const app = apportPaye ? appCalc : 0;
        document.getElementById('valeurSouscription').textContent = formatMontant(valeurSouscription);
        document.getElementById('pourcentageApport').textContent = apportPaye ? pct : 0;
        document.getElementById('apportInitial').textContent = formatMontant(app);
        document.getElementById('fraisSouscription').textContent = formatMontant(fr);
        document.getElementById('valeur_souscription_input').value = valeurSouscription;
        document.getElementById('apport_initial_input').value = app;
        document.getElementById('frais_souscription_input').value = fr;
    }
    function generateRecap() {
        const data = new FormData(document.getElementById('editSubscriptionForm'));
        const cat = data.get('clientCategory') || '-';
        let mutuelleLine = '';
        let orgLine = '';
        if (String(cat).toLowerCase() === 'association syndicat mutuelle') {
            orgLine = `<div class="recap-item"><strong>Organisation:</strong> ${data.get('organisation_type') || '-'}</div>`;
        }
        if ((data.get('organisation_type') || '').toLowerCase() === 'mutuelle' || String(cat).toLowerCase() === 'mutuelle') {
            const mutSel = document.getElementById('mutuelleSelect');
            const mutText = (mutSel && mutSel.selectedIndex > 0) ? mutSel.options[mutSel.selectedIndex].text : '-';
            mutuelleLine = `<div class="recap-item"><strong>Mutuelle:</strong> ${mutText}</div>`;
        }
        const logement = (data.get('housingType') || '').includes('|') ? (data.get('housingType') || '').split('|')[1] : (data.get('housingType') || '-');
        document.getElementById('recapContent').innerHTML = `
            <div class="recap-item"><strong>Catégorie:</strong> ${cat}</div>
            ${orgLine}
            ${mutuelleLine}
            <div class="recap-item"><strong>Nom:</strong> ${data.get('nom')} ${data.get('prenom')}</div>
            <div class="recap-item"><strong>Tél:</strong> ${data.get('phone')}</div>
            <div class="recap-item"><strong>Logement:</strong> ${logement}</div>
            <div class="recap-item"><strong>Prix:</strong> ${formatMontant(data.get('valeur_souscription'))} FCFA</div>
            <div class="recap-item"><strong>Apport initial à payer:</strong> ${String(data.get('apport_initial_paye_par_client') || '0') === '1' ? 'Oui' : 'Non'}</div>
            <div class="recap-item"><strong>Apport initial:</strong> ${formatMontant(data.get('apport_initial') || 0)} FCFA</div>
            <div class="recap-item"><strong>Frais souscription:</strong> ${formatMontant(data.get('frais_souscription') || 0)} FCFA</div>
        `;
    }
    function updateFileName(i) { document.getElementById('fileName').textContent = i.files[0]?.name || ''; }
    document.addEventListener('DOMContentLoaded', () => {
        const cat = document.getElementById('clientCategory').value;
        if(cat) document.querySelectorAll('.category-card').forEach(c => { if(c.innerText.includes(cat)) c.classList.add('selected'); });
        toggleOrganisationFields();
        const pm = document.getElementById('paymentMode').value;
        if(pm) document.querySelectorAll('.payment-options .option-card').forEach(c => { if(c.innerText.trim() === pm.replace('_',' ')) c.classList.add('selected'); });
        chargerBiensImmobiliers();
        toggleConjoint();
        showStep(0);

        const apportCheckbox = document.getElementById('apport_initial_paye_par_client');
        if (apportCheckbox) {
            apportCheckbox.addEventListener('change', function () {
                const housingValue = document.getElementById('housingType')?.value || '';
                if (!housingValue.includes('|')) return;
                const projetId = document.querySelector('select[name="program"]')?.value;
                const [bienId] = housingValue.split('|');
                const b = (biensImmobiliers[projetId] || []).find(x => String(x.id) === String(bienId));
                if (b) calculer(b);
            });
        }
        const mutSel = document.getElementById('mutuelleSelect');
        if (mutSel) {
            mutSel.addEventListener('change', function() {
                const housingValue = document.getElementById('housingType')?.value || '';
                if (!housingValue.includes('|')) return;
                const pid = document.querySelector('select[name="program"]')?.value;
                const [bienId] = housingValue.split('|');
                const bien = (biensImmobiliers[pid] || []).find(b => String(b.id) === String(bienId));
                if (bien) calculer(bien);
            });
        }
    });

    function toggleOrganisationFields() {
        const cat = (document.getElementById('clientCategory')?.value || '').toLowerCase();
        const orgWrapper = document.getElementById('organisationTypeWrapper');
        const orgRadios = document.querySelectorAll('input[name="organisation_type"]');
        const wrapper = document.getElementById('mutuelleSelectWrapper');
        if (!orgWrapper || !wrapper) return;
        const sel = document.getElementById('mutuelleSelect');
        const isOrg = cat === 'association syndicat mutuelle';
        orgWrapper.style.display = isOrg ? 'block' : 'none';
        orgRadios.forEach(r => {
            r.disabled = !isOrg;
            if (isOrg) r.setAttribute('required', 'required');
            else r.removeAttribute('required');
        });

        if (!isOrg) {
            orgRadios.forEach(r => { r.checked = false; });
            wrapper.style.display = 'none';
            if (sel) { sel.value = ''; sel.removeAttribute('required'); sel.disabled = true; }
            return;
        }

        const orgType = (document.querySelector('input[name="organisation_type"]:checked')?.value || '').toLowerCase();
        if (orgType === 'mutuelle') {
            wrapper.style.display = 'block';
            if (sel) { sel.disabled = false; sel.setAttribute('required', 'required'); }
        } else {
            wrapper.style.display = 'none';
            if (sel) { sel.value = ''; sel.removeAttribute('required'); sel.disabled = true; }
        }
    }
</script>
@endsection

@extends('layouts.chef_commercial')

@section('title','Corriger une souscription - Chef Commercial')
@section('content')
@php
    // Assurer la disponibilité des données comme dans la création
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
    .summary-line.total { font-weight: bold; font-size: 16px; color: #2c3e50; padding-top: 10px; border-top: 1px solid #ddd; margin-top: 10px; }
    .warning-box { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 6px; margin: 20px 0; font-size: 13px; color: #856404; }
    .buttons { display: flex; justify-content: space-between; gap: 15px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0; }
    button { padding: 14px 30px; border: none; border-radius: 6px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; flex: 1; }
    .btn-secondary { background: #ecf0f1; color: #555; }
    .btn-secondary:hover { background: #d5dbdb; }
    .btn-primary { background: #2c5f8d; color: white; }
    .btn-primary:hover { background: #234a6e; }
    .success-screen { display: none; text-align: center; padding: 60px 30px; }
    .success-screen.active { display: block; }
    .success-icon { width: 80px; height: 80px; margin: 0 auto 25px; background: #27ae60; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; color: white; }
    .recap-box { background: #f8f9fa; border-radius: 6px; padding: 20px; text-align: left; margin: 30px 0; }
    .recap-item { margin-bottom: 8px; font-size: 14px; color: #555; }
    .recap-item strong { color: #2c3e50; }
    @media (max-width: 600px) { .category-selection { grid-template-columns: 1fr; } .form-row { grid-template-columns: 1fr; } .housing-options, .payment-options { grid-template-columns: 1fr; } }
</style>

<div class="container">
    <div class="header">
        <div class="logo-display">
            <img src="{{ asset('LOGO.png') }}" alt="Logo" style="max-width: 200px; height: auto;">
        </div><br>
        <h2>Correction de souscription #{{ $souscription->id }}</h2>
        <p>Promoteur immobilier agréé</p>
        @if($souscription->statut === 'en_attente_correction')
            <div class="badge-info">Statut : <strong>En attente de correction</strong></div>
        @endif
    </div>

    <div class="progress-bar">
        <div class="progress-fill" id="progressFill" style="width: 20%"></div>
    </div>

    <div class="step-indicator" id="stepIndicator">1/5</div>

    <form id="editSubscriptionForm" action="{{ route('chef_commercial.souscriptions.update', $souscription->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger" style="margin: 20px 30px; padding: 15px; border-radius: 6px; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24;">
                <strong><i class="bi bi-exclamation-triangle"></i> Erreurs de validation :</strong>
                <ul style="margin-top: 10px; margin-bottom: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ÉTAPE 0: Sélection catégorie -->
        <div class="step active" id="step0">
            <h3>Choisissez la catégorie de client</h3>

            <div class="category-selection">
                <div class="category-card" data-category="Client individuel" onclick="selectCategory(this, 'Client individuel')">
                    <div class="category-icon"><i class="bi bi-person"></i></div>
                    <div class="category-label">Client<br>individuel</div>
                </div>

                <div class="category-card" data-category="Association Syndicat Mutuelle" onclick="selectCategory(this, 'Association Syndicat Mutuelle')">
                    <div class="category-icon"><i class="bi bi-people"></i></div>
                    <div class="category-label">Association<br>Syndicat<br>Mutuelle</div>
                </div>

                <div class="category-card" data-category="Client diaspora" onclick="selectCategory(this, 'Client diaspora')">
                    <div class="category-icon"><i class="bi bi-globe"></i></div>
                    <div class="category-label">Client<br>diaspora</div>
                </div>
            </div>

            @php
                $categorieClient = old('clientCategory', '');
                if (!$categorieClient && $souscription->categorie_client) {
                    $categorieMap = [
                        'individuel' => 'Client individuel',
                        'association' => 'Association Syndicat Mutuelle',
                        'diaspora' => 'Client diaspora'
                    ];
                    $categorieClient = $categorieMap[$souscription->categorie_client] ?? '';
                }
            @endphp
            <input type="hidden" name="clientCategory" id="clientCategory" required value="{{ $categorieClient }}">

            <div class="buttons">
                <button type="button" class="btn-primary" onclick="nextStep()" id="continueCategory" disabled>Continuer</button>
            </div>
        </div>

        <!-- ÉTAPE 1 -->
        <div class="step" id="step1">
            <h3>Informations du client</h3>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="bi bi-person"></i> Nom et Prénom</label>
                    <input type="text" name="fullName" required placeholder="Nom complet" class="@error('fullName') is-invalid @enderror" value="{{ old('fullName', $souscription->nom_prenom ?? '') }}">
                    @error('fullName')
                        <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label><i class="bi bi-calendar"></i> Date de naissance</label>
                    <input type="date" name="birthDate" required class="@error('birthDate') is-invalid @enderror" value="{{ old('birthDate', $souscription->date_naissance ? $souscription->date_naissance->format('Y-m-d') : '') }}">
                    @error('birthDate')
                        <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="bi bi-geo-alt"></i> Lieu de naissance</label>
                    <input type="text" name="birthPlace" required placeholder="Ville" class="@error('birthPlace') is-invalid @enderror" value="{{ old('birthPlace', $souscription->lieu_naissance ?? '') }}">
                    @error('birthPlace')
                        <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label><i class="bi bi-globe"></i> Nationalité</label>
                    <input type="text" name="nationality" required placeholder="Pays" class="@error('nationality') is-invalid @enderror" value="{{ old('nationality', $souscription->nationalite ?? '') }}">
                    @error('nationality')
                        <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="bi bi-person-plus"></i> Nombre d'enfants</label>
                    <input type="number" name="children" min="0" required placeholder="0" class="@error('children') is-invalid @enderror" value="{{ old('children', $souscription->nombre_enfants ?? 0) }}">
                    @error('children')
                        <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label><i class="bi bi-person-heart"></i> Ayant droit</label>
                    <input type="text" name="heirs" required placeholder="Nom de l'ayant droit" class="@error('heirs') is-invalid @enderror" value="{{ old('heirs', is_array($souscription->ayant_droit) ? implode(', ', $souscription->ayant_droit) : $souscription->ayant_droit ?? '') }}">
                    @error('heirs')
                        <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label><i class="bi bi-envelope"></i> Email</label>
                <input type="email" name="email" required placeholder="exemple@email.com" class="@error('email') is-invalid @enderror" value="{{ old('email', $souscription->email ?? '') }}">
                @error('email')
                    <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label><i class="bi bi-cash"></i> Salaire mensuel</label>
                <input type="text" name="salary" required placeholder="Montant en FCFA" class="@error('salary') is-invalid @enderror" value="{{ old('salary', $souscription->salaire_mensuel ?? '') }}">
                @error('salary')
                    <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Situation matrimoniale:</label>
                @php
                    $situationMatrimoniale = old('maritalStatus', '');
                    if (!$situationMatrimoniale && $souscription->situation_matrimoniale) {
                        $situationMap = [
                            'celibataire' => 'Célibataire',
                            'divorce' => 'Divorcé(e)',
                            'marie' => 'Marié(e)',
                            'veuf' => 'Veuf(ve)'
                        ];
                        $situationMatrimoniale = $situationMap[$souscription->situation_matrimoniale] ?? '';
                    }
                @endphp
                <div class="checkbox-group @error('maritalStatus') is-invalid @enderror">
                    <div class="checkbox-item"><input type="checkbox" name="maritalStatus" value="Célibataire" id="cel" {{ $situationMatrimoniale == 'Célibataire' ? 'checked' : '' }} onclick="uncheckOthers(this, 'maritalStatus')"><label for="cel">Célibataire</label></div>
                    <div class="checkbox-item"><input type="checkbox" name="maritalStatus" value="Divorcé(e)" id="div" {{ $situationMatrimoniale == 'Divorcé(e)' ? 'checked' : '' }} onclick="uncheckOthers(this, 'maritalStatus')"><label for="div">Divorcé(e)</label></div>
                    <div class="checkbox-item"><input type="checkbox" name="maritalStatus" value="Marié(e)" id="mar" {{ $situationMatrimoniale == 'Marié(e)' ? 'checked' : '' }} onclick="uncheckOthers(this, 'maritalStatus')"><label for="mar">Marié(e)</label></div>
                    <div class="checkbox-item"><input type="checkbox" name="maritalStatus" value="Veuf(ve)" id="veuf" {{ $situationMatrimoniale == 'Veuf(ve)' ? 'checked' : '' }} onclick="uncheckOthers(this, 'maritalStatus')"><label for="veuf">Veuf(ve)</label></div>
                </div>
                @error('maritalStatus')
                    <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="buttons">
                <button type="button" class="btn-secondary" onclick="prevStep()">Retour</button>
                <button type="button" class="btn-primary" onclick="nextStep()">Continuer</button>
            </div>
        </div>

        <!-- ÉTAPE 2 -->
        <div class="step" id="step2">
            <h3>Pièce d'identité et programme</h3>

            <div class="form-group">
                <label>Nature de la pièce:</label>
                @php
                    $naturePiece = old('idType', '');
                    if (!$naturePiece && $souscription->nature_piece) {
                        $pieceMap = [
                            'cni' => 'CNI',
                            'passeport' => 'Passeport',
                            'carte_consulaire' => 'Carte consulaire'
                        ];
                        $naturePiece = $pieceMap[$souscription->nature_piece] ?? '';
                    }
                @endphp
                <div class="checkbox-group @error('idType') is-invalid @enderror">
                    <div class="checkbox-item"><input type="checkbox" name="idType" value="CNI" id="cni" {{ $naturePiece == 'CNI' ? 'checked' : '' }} onclick="uncheckOthers(this, 'idType')"><label for="cni">CNI</label></div>
                    <div class="checkbox-item"><input type="checkbox" name="idType" value="Passeport" id="pass" {{ $naturePiece == 'Passeport' ? 'checked' : '' }} onclick="uncheckOthers(this, 'idType')"><label for="pass">Passeport</label></div>
                    <div class="checkbox-item"><input type="checkbox" name="idType" value="Carte consulaire" id="cons" {{ $naturePiece == 'Carte consulaire' ? 'checked' : '' }} onclick="uncheckOthers(this, 'idType')"><label for="cons">Carte consulaire</label></div>
                </div>
                @error('idType')<div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label><i class="bi bi-file-text"></i> Numéro CNI / Passeport</label>
                <input type="text" name="idNumber" required placeholder="Numéro d'identification" class="@error('idNumber') is-invalid @enderror" value="{{ old('idNumber', $souscription->numero_piece ?? '') }}">
                @error('idNumber')<div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Téléverser la CNI/Passeport</label>
                @if($souscription->fichier_piece)
                    <div style="margin-bottom: 10px; padding: 10px; background: #e8f4f8; border-radius: 6px; font-size: 13px;">
                        <i class="bi bi-file-earmark-check" style="color: #27ae60;"></i> 
                        <strong>Fichier actuel :</strong> 
                        <a href="{{ Storage::url($souscription->fichier_piece) }}" target="_blank" style="color: #2c5f8d; text-decoration: underline;">
                            Voir le document
                        </a>
                        <br><small style="color: #666;">Téléchargez un nouveau fichier pour le remplacer (optionnel)</small>
                    </div>
                @endif
                <div class="file-upload-area @error('idFile') is-invalid @enderror" onclick="document.getElementById('fileInput').click()">
                    <div class="file-upload-icon"><i class="bi bi-cloud-upload"></i></div>
                    <div class="file-upload-text">Joindre un document en PDF, JPG, PNG<br>(Taille maximale 10 Mo)</div>
                </div>
                <input type="file" id="fileInput" name="idFile" accept=".pdf,.jpg,.jpeg,.png" style="display:none" onchange="updateFileName(this)" class="@error('idFile') is-invalid @enderror">
                <div id="fileName" style="margin-top: 10px; font-size: 13px; color: #27ae60;"></div>
                @error('idFile')<div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Programme</label>
                <select name="program" required class="@error('program') is-invalid @enderror">
                    <option value="">-- Sélectionnez un programme --</option>
                    @foreach($projets as $projet)
                        <option value="{{ $projet->id }}"
                            {{ (old('program') == $projet->id) || ($souscription->programme == $projet->id) ? 'selected' : '' }}>
                            {{ $projet->nom }}
                        </option>
                    @endforeach
                </select>
                @error('program')<div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>La durée du contrat</label>
                <div class="form-row">
                    <div>
                        <label style="font-size: 12px; color: #888;"><i class="bi bi-calendar"></i> Date de début</label>
                        <input type="date" name="startDate" required class="@error('startDate') is-invalid @enderror" value="{{ old('startDate', $souscription->date_debut ? $souscription->date_debut->format('Y-m-d') : '') }}">
                        @error('startDate')<div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label style="font-size: 12px; color: #888;"><i class="bi bi-calendar"></i> Date de fin</label>
                        <input type="date" name="endDate" required class="@error('endDate') is-invalid @enderror" value="{{ old('endDate', $souscription->date_fin ? $souscription->date_fin->format('Y-m-d') : '') }}">
                        @error('endDate')<div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="buttons">
                <button type="button" class="btn-secondary" onclick="prevStep()">Retour</button>
                <button type="button" class="btn-primary" onclick="nextStep()">Continuer</button>
            </div>
        </div>

        <!-- ÉTAPE 3 -->
        <div class="step" id="step3">
            <h3>Type de logement et paiement</h3>

            <div class="form-group">
                <label>Types de logement :</label>
                <div class="housing-options @error('housingType') is-invalid @enderror" id="housingOptionsContainer"></div>
                <input type="hidden" name="housingType" id="housingType" required class="@error('housingType') is-invalid @enderror" value="{{ old('housingType', $souscription->bien_immobilier_id ? $souscription->bien_immobilier_id . '|' . ($souscription->bienImmobilier->titre ?? '') : '') }}">
                @error('housingType')<div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Mode paiement</label>
                <div class="payment-options @error('paymentMode') is-invalid @enderror">
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'ESPECES')"><div class="option-checkbox"></div><span>ESPECES</span></div>
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'VIREMENT')"><div class="option-checkbox"></div><span>VIREMENT</span></div>
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'MOBILE_MONEY')"><div class="option-checkbox"></div><span>MOBILE_MONEY</span></div>
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'TEMPERAMENT')"><div class="option-checkbox"></div><span>TEMPERAMENT</span></div>
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'CREDIT_BANCAIRE')"><div class="option-checkbox"></div><span>CREDIT_BANCAIRE</span></div>
                </div>
                <input type="hidden" name="paymentMode" id="paymentMode" required class="@error('paymentMode') is-invalid @enderror" value="{{ old('paymentMode', $souscription->mode_paiement ?? '') }}">
                @error('paymentMode')<div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div class="summary-box">
                <h4>💰 Valeur de la souscription : <span id="valeurSouscription">-</span> FCFA</h4>
                <div class="summary-line"><span>Apport initial (<span id="pourcentageApport">-</span>%)</span><strong><span id="apportInitial">-</span> FCFA</strong></div>
            </div>

            <div class="warning-box">⚠️ Les frais de souscription s'élèvent à <span id="fraisSouscription">-</span> FCFA (non remboursables)</div>

            <input type="hidden" name="valeur_souscription" id="valeur_souscription_input" value="{{ old('valeur_souscription', $souscription->prix_logement ?? '30000000') }}">
            <input type="hidden" name="apport_initial" id="apport_initial_input" value="{{ old('apport_initial', $souscription->apport_initial ?? '3000000') }}">
            <input type="hidden" name="frais_souscription" id="frais_souscription_input" value="{{ old('frais_souscription', $souscription->frais_souscription ?? '500000') }}">

            <div class="buttons">
                <button type="button" class="btn-secondary" onclick="prevStep()">Retour</button>
                <button type="button" class="btn-primary" onclick="nextStep()">Continuer</button>
            </div>
        </div>

        <!-- ÉTAPE 4: Récapitulatif -->
        <div class="step" id="step4">
            <h3>Récapitulatif des corrections</h3>
            <div class="recap-box" id="recapContent"></div>
            <div class="form-group">
                <div class="checkbox-item @error('certify') is-invalid @enderror">
                    <input type="checkbox" id="certify" name="certify" required class="@error('certify') is-invalid @enderror" {{ old('certify') ? 'checked' : '' }}>
                    <label for="certify">Je certifie que les informations corrigées sont exactes et j'autorise leur traitement</label>
                </div>
                @error('certify')<div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>
            <div class="buttons">
                <button type="button" class="btn-secondary" onclick="prevStep()">Retour</button>
                <button type="submit" class="btn-primary">Enregistrer les corrections</button>
            </div>
        </div>
    </form>

    <div class="success-screen" id="successScreen">
        <div class="success-icon"><i class="bi bi-check-circle"></i></div>
        <h3>Modifications enregistrées</h3>
        <button type="button" class="btn-primary" onclick="window.location.href='{{ route('chef_commercial.souscriptions.corrigees') }}'">Retour aux dossiers</button>
    </div>
</div>

<script src="{{ asset('debug-edit.js') }}"></script>
    <script>
        let currentStep = 0;
    const totalSteps = 5;
    const biensImmobiliers = @json($biensImmobiliers ?? []);

    function updateProgress() {
        const progress = ((currentStep + 1) / totalSteps) * 100;
        document.getElementById('progressFill').style.width = progress + '%';
        document.getElementById('stepIndicator').textContent = (currentStep + 1) + '/' + totalSteps;
    }
    function showStep(n) {
        document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
        const step = document.getElementById('step' + n);
        if (step) step.classList.add('active');
        updateProgress();
    }
    function selectCategory(element, value) {
        document.querySelectorAll('.category-card').forEach(c => c.classList.remove('selected'));
        element.classList.add('selected');
        document.getElementById('clientCategory').value = value;
        document.getElementById('continueCategory').disabled = false;
    }
    function selectOption(element, fieldName, value) {
        const parent = element.parentElement;
        parent.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));
        element.classList.add('selected');
        document.getElementById(fieldName).value = value;
    }
    function updateFileName(input) {
        const fileName = input.files[0]?.name || '';
        document.getElementById('fileName').textContent = fileName ? 'Fichier sélectionné: ' + fileName : '';
    }
    function nextStep() {
        if (validateStep(currentStep)) {
            if (currentStep === 3) generateRecap();
            currentStep++; showStep(currentStep);
        }
    }
    function prevStep() { currentStep--; showStep(currentStep); }
    function validateStep(step) {
        const currentStepElement = document.getElementById('step' + step);
        const requiredFields = currentStepElement.querySelectorAll('[required]');
        for (let field of requiredFields) {
            if (!field.value || (typeof field.value === 'string' && !field.value.trim())) {
                alert('Veuillez remplir tous les champs obligatoires');
                field.focus();
                return false;
            }
        }
        return true;
    }
    function generateRecap() {
        const formData = new FormData(document.getElementById('editSubscriptionForm'));
        let recapHTML = '';
        recapHTML += '<div class="recap-item"><strong>Catégorie:</strong> ' + (formData.get('clientCategory') || '-') + '</div>';
        recapHTML += '<div class="recap-item"><strong>Nom:</strong> ' + (formData.get('fullName') || '-') + '</div>';
        recapHTML += '<div class="recap-item"><strong>Nationalité:</strong> ' + (formData.get('nationality') || '-') + '</div>';
        recapHTML += '<div class="recap-item"><strong>Type de logement:</strong> ' + (formData.get('housingType') || '-') + '</div>';
        recapHTML += '<div class="recap-item"><strong>Mode de paiement:</strong> ' + (formData.get('paymentMode') || '-') + '</div>';
        recapHTML += '<div class="recap-item"><strong>Valeur de souscription:</strong> ' + formatMontant(parseInt(formData.get('valeur_souscription') || '0')) + ' FCFA</div>';
        recapHTML += '<div class="recap-item"><strong>Apport initial:</strong> ' + formatMontant(parseInt(formData.get('apport_initial') || '0')) + ' FCFA</div>';
        recapHTML += '<div class="recap-item"><strong>Frais de souscription:</strong> ' + formatMontant(parseInt(formData.get('frais_souscription') || '0')) + ' FCFA [non remboursables]</div>';
        document.getElementById('recapContent').innerHTML = recapHTML;
    }
    document.getElementById('editSubscriptionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (!document.getElementById('certify').checked) { alert('Veuillez certifier que les informations sont exactes'); return; }
        this.submit();
    });
    function formatMontant(montant) { return new Intl.NumberFormat('fr-FR').format(isNaN(montant) ? 0 : montant); }
    
    function uncheckOthers(element, fieldName) {
        // Décocher toutes les autres checkboxes du même groupe
        document.querySelectorAll('input[name="' + fieldName + '"]').forEach(function(checkbox) {
            if (checkbox !== element) {
                checkbox.checked = false;
            }
        });
    }
    function calculerValeursSouscription() {
        const programmeSelect = document.querySelector('select[name="program"]');
        const housingTypeValue = document.getElementById('housingType').value;
        if (!programmeSelect || !programmeSelect.value || !housingTypeValue) return;
        const [bienId] = housingTypeValue.split('|');
        const projetId = programmeSelect.value;
        const biens = (biensImmobiliers && biensImmobiliers[projetId]) ? biensImmobiliers[projetId] : [];
        const bien = biens.find(b => b.id == bienId);
        if (bien && bien.prix) {
            const valeurSouscription = bien.prix;
            const pourcentageApport = parseFloat(bien.pourcentage_apport) || 10;
            const fraisSouscription = parseFloat(bien.frais_souscription) || 500000;
            const apportInitial = parseFloat(bien.apport_initial) || Math.round(valeurSouscription * (pourcentageApport / 100));
            document.getElementById('valeurSouscription').textContent = formatMontant(valeurSouscription);
            document.getElementById('pourcentageApport').textContent = pourcentageApport;
            document.getElementById('apportInitial').textContent = formatMontant(apportInitial);
            document.getElementById('fraisSouscription').textContent = formatMontant(fraisSouscription);
            document.getElementById('valeur_souscription_input').value = valeurSouscription;
            document.getElementById('apport_initial_input').value = apportInitial;
            document.getElementById('frais_souscription_input').value = fraisSouscription;
        }
    }
    function chargerBiensImmobiliers() {
        const programmeSelect = document.querySelector('select[name="program"]');
        const housingContainer = document.getElementById('housingOptionsContainer');
        const housingTypeInput = document.getElementById('housingType');
        if (!programmeSelect || !programmeSelect.value || !housingContainer) return;
        
        const projetId = programmeSelect.value;
        const biens = (biensImmobiliers && biensImmobiliers[projetId]) ? biensImmobiliers[projetId] : [];
        
        // Sauvegarder la valeur actuelle avant de vider
        const currentHousingValue = housingTypeInput.value;
        
        housingContainer.innerHTML = '';
        
        if (biens.length === 0) { 
            housingContainer.innerHTML = '<div style="padding: 20px; text-align: center; color: #666;">Aucun bien immobilier disponible pour ce programme</div>'; 
            housingTypeInput.value = '';
            return; 
        }
        
        biens.forEach(function(bien) {
            const optionCard = document.createElement('div'); 
            optionCard.className = 'option-card';
            optionCard.setAttribute('data-bien-id', bien.id);
            optionCard.onclick = function() { 
                selectOption(this, 'housingType', bien.id + '|' + bien.titre); 
                calculerValeursSouscription(); 
            };
            optionCard.innerHTML = '<div class="option-checkbox"></div><span>' + bien.titre + '</span>'+
                '<div style="font-size: 12px; color: #666; margin-top: 5px;">Prix: ' + formatMontant(bien.prix) + ' FCFA</div>';
            housingContainer.appendChild(optionCard);
            
            // Restaurer la sélection si c'est le bien actuel
            if (currentHousingValue && currentHousingValue.startsWith(bien.id + '|')) {
                optionCard.classList.add('selected');
                housingTypeInput.value = currentHousingValue;
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        const programmeSelect = document.querySelector('select[name="program"]');
        if (programmeSelect) {
            programmeSelect.addEventListener('change', function() { 
                chargerBiensImmobiliers(); 
                calculerValeursSouscription(); 
            });
            
            // Charger les biens immobiliers si un programme est déjà sélectionné
            if (programmeSelect.value) { 
                chargerBiensImmobiliers(); 
                
                // Après le chargement, restaurer la sélection du bien immobilier
                setTimeout(function() {
                    const housingTypeValue = document.getElementById('housingType').value;
                    if (housingTypeValue) {
                        const housingOptions = document.querySelectorAll('#housingOptionsContainer .option-card');
                        housingOptions.forEach(card => {
                            if (housingTypeValue.includes('|')) {
                                const [bienId, bienTitre] = housingTypeValue.split('|');
                                // Vérifier si la carte correspond au bien ID
                                const cardOnclick = card.getAttribute('onclick');
                                if (cardOnclick && cardOnclick.includes(bienId)) {
                                    card.classList.add('selected');
                                    calculerValeursSouscription();
                                }
                            }
                        });
                    }
                }, 300);
            }
        }
        // Restaurer catégorie / paiement si old()
        const clientCategory = document.getElementById('clientCategory').value;
        if (clientCategory) { 
            document.querySelectorAll('.category-card').forEach(card => { 
                const cardCategory = card.getAttribute('data-category');
                if (cardCategory === clientCategory) { 
                    card.classList.add('selected'); 
                    document.getElementById('continueCategory').disabled = false; 
                } 
            }); 
        }
        
        // Restaurer le mode de paiement
        const paymentMode = document.getElementById('paymentMode').value;
        if (paymentMode) { 
            document.querySelectorAll('.payment-options .option-card').forEach(card => { 
                if (card.textContent.trim() === paymentMode) { 
                    card.classList.add('selected'); 
                } 
            }); 
        }
        showStep(0);
    });
</script>
@endsection
@extends('layouts.chef_commercial')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #003d82 0%, #0056b3 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        padding: 20px;
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .header {
        background: white;
        padding: 30px 20px 20px;
        text-align: center;
        border-bottom: 1px solid #e0e0e0;
    }

    .header h2 {
        font-size: 20px;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .header p {
        font-size: 14px;
        color: #7f8c8d;
    }

    .progress-bar {
        background: #ecf0f1;
        height: 4px;
        position: relative;
    }

    .progress-fill {
        background: #2c5f8d;
        height: 100%;
        transition: width 0.3s ease;
    }

    .step-indicator {
        text-align: right;
        padding: 15px 30px;
        font-size: 14px;
        color: #7f8c8d;
        font-weight: 600;
    }

    .step {
        display: none;
        padding: 30px;
    }

    .step.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .step h3 {
        font-size: 18px;
        color: #2c3e50;
        margin-bottom: 25px;
    }

    .category-selection {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 30px;
    }

    .category-card {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .category-card:hover {
        border-color: #2c5f8d;
        background: #f8f9fa;
    }

    .category-card.selected {
        border-color: #2c5f8d;
        background: #e8f4f8;
    }

    .category-icon {
        width: 50px;
        height: 50px;
        margin: 0 auto 10px;
        background: #ecf0f1;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .category-card.selected .category-icon {
        background: #2c5f8d;
        color: white;
    }

    .category-label {
        font-size: 13px;
        color: #2c3e50;
        font-weight: 500;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    label {
        display: block;
        font-size: 14px;
        color: #555;
        margin-bottom: 8px;
        font-weight: 500;
    }

    input, select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s ease;
        background: white;
    }

    input:focus, select:focus {
        outline: none;
        border-color: #2c5f8d;
    }

    .file-upload-area {
        border: 2px dashed #ddd;
        border-radius: 6px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .file-upload-area:hover {
        border-color: #2c5f8d;
        background: #f0f8ff;
    }

    .file-upload-icon {
        font-size: 40px;
        color: #95a5a6;
        margin-bottom: 10px;
    }

    .file-upload-text {
        color: #7f8c8d;
        font-size: 13px;
    }

    .checkbox-group {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .housing-options, .payment-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 10px;
    }

    .option-card {
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .option-card:hover {
        border-color: #2c5f8d;
        background: #f8f9fa;
    }

    .option-card.selected {
        border-color: #2c5f8d;
        background: #e8f4f8;
    }

    .option-checkbox {
        width: 20px;
        height: 20px;
        border: 2px solid #ddd;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .option-card.selected .option-checkbox {
        background: #2c5f8d;
        border-color: #2c5f8d;
        color: white;
    }

    .option-card.selected .option-checkbox::before {
        content: '✓';
        font-size: 14px;
    }

    .summary-box {
        background: #f8f9fa;
        border-left: 4px solid #2c5f8d;
        padding: 20px;
        border-radius: 6px;
        margin: 25px 0;
    }

    .summary-box h4 {
        color: #2c3e50;
        margin-bottom: 15px;
        font-size: 16px;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 14px;
        color: #555;
    }

    .warning-box {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 15px;
        border-radius: 6px;
        margin: 20px 0;
        font-size: 13px;
        color: #856404;
    }

    .buttons {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    button {
        padding: 14px 30px;
        border: none;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        flex: 1;
    }

    .btn-secondary {
        background: #ecf0f1;
        color: #555;
    }

    .btn-secondary:hover {
        background: #d5dbdb;
    }

    .btn-primary {
        background: #2c5f8d;
        color: white;
    }

    .btn-primary:hover {
        background: #234a6e;
    }

    .success-screen {
        display: none;
        text-align: center;
        padding: 60px 30px;
    }

    .success-screen.active {
        display: block;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 25px;
        background: #27ae60;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        color: white;
    }

    .recap-box {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 20px;
        text-align: left;
        margin: 30px 0;
    }

    .recap-item {
        margin-bottom: 8px;
        font-size: 14px;
        color: #555;
    }

    .recap-item strong {
        color: #2c3e50;
    }

    @media (max-width: 600px) {
        .category-selection {
            grid-template-columns: 1fr;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .housing-options, .payment-options {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container">
    <div class="header">
        <div class="logo-display">
            <img src="{{ asset('LOGO.png') }}" alt="Logo" style="max-width: 200px; height: auto;">
        </div><br>
        <h2>Nouvelle souscription</h2>
        <p>Promoteur immobilier agréé</p>
    </div>

    <div class="progress-bar" @if (session('success')) style="display:none" @endif>
        <div class="progress-fill" id="progressFill" style="width: 20%"></div>
    </div>

    <div class="step-indicator" id="stepIndicator" @if (session('success')) style="display:none" @endif>1/5</div>

    <form id="subscriptionForm" action="{{ route('chef_commercial.souscriptions.store') }}" method="POST" enctype="multipart/form-data" @if (session('success')) style="display:none" @endif>
        @csrf

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
                <div class="category-card" onclick="selectCategory(this, 'Client individuel')">
                    <div class="category-icon"><i class="bi bi-person"></i></div>
                    <div class="category-label">Client<br>individuel</div>
                </div>

                <div class="category-card" onclick="selectCategory(this, 'Association Syndicat Mutuelle')">
                    <div class="category-icon"><i class="bi bi-people"></i></div>
                    <div class="category-label">Association<br>Syndicat<br>Mutuelle</div>
                </div>

                <div class="category-card" onclick="selectCategory(this, 'Client diaspora')">
                    <div class="category-icon"><i class="bi bi-globe"></i></div>
                    <div class="category-label">Client<br>diaspora</div>
                </div>
            </div>

            <input type="hidden" name="clientCategory" id="clientCategory" required value="{{ old('clientCategory') }}">

            <div id="organisationTypeWrapper" style="display:none; margin-top: 15px;">
                <label>Organisation</label>
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="radio" name="organisation_type" value="Association" id="org_association" {{ old('organisation_type') == 'Association' ? 'checked' : '' }} onchange="toggleOrganisationFields()">
                        <label for="org_association">Association</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="radio" name="organisation_type" value="Syndicat" id="org_syndicat" {{ old('organisation_type') == 'Syndicat' ? 'checked' : '' }} onchange="toggleOrganisationFields()">
                        <label for="org_syndicat">Syndicat</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="radio" name="organisation_type" value="Mutuelle" id="org_mutuelle" {{ old('organisation_type') == 'Mutuelle' ? 'checked' : '' }} onchange="toggleOrganisationFields()">
                        <label for="org_mutuelle">Mutuelle</label>
                    </div>
                </div>
            </div>

            <div class="mutuelle-select" id="mutuelleSelectWrapper" style="display:none; margin-top: 15px;">
                <label>Mutuelle</label>
                <select name="mutuelle_id" id="mutuelleSelect" disabled>
                    <option value="">-- Sélectionnez une mutuelle --</option>
                    @isset($mutuelles)
                        @foreach($mutuelles as $m)
                            <option value="{{ $m->id }}" data-project="{{ $m->project_id ?? '' }}" {{ (string)old('mutuelle_id') === (string)$m->id ? 'selected' : '' }}>{{ $m->nom }}</option>
                        @endforeach
                    @endisset
                </select>
                <small class="text-muted">Si la mutuelle propose un prix spécial pour le bien choisi, il sera appliqué automatiquement.</small>
            </div>

            <div class="buttons">
                <button type="button" class="btn-primary" onclick="nextStep()" id="continueCategory" disabled>Continuer</button>
            </div>
        </div>

        <!-- ÉTAPE 1 -->
        <div class="step" id="step1">
            <h3>Informations personnelles</h3>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="bi bi-person"></i> Nom</label>
                    <input type="text" name="nom" required placeholder="Nom" class="@error('nom') is-invalid @enderror" value="{{ old('nom') }}">
                </div>
                <div class="form-group">
                    <label><i class="bi bi-person"></i> Prénom</label>
                    <input type="text" name="prenom" required placeholder="Prénom" class="@error('prenom') is-invalid @enderror" value="{{ old('prenom') }}">
                </div>
            </div>

            <div class="form-group">
                <label><i class="bi bi-calendar"></i> Date de naissance</label>
                <input type="date" name="birthDate" required class="@error('birthDate') is-invalid @enderror" value="{{ old('birthDate') }}">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="bi bi-geo-alt"></i> Lieu de naissance</label>
                    <input type="text" name="birthPlace" required placeholder="Ville" class="@error('birthPlace') is-invalid @enderror" value="{{ old('birthPlace') }}">
                </div>
                <div class="form-group">
                    <label><i class="bi bi-globe"></i> Nationalité</label>
                    <input type="text" name="nationality" required placeholder="Nationalité" class="@error('nationality') is-invalid @enderror" value="{{ old('nationality') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="bi bi-person-plus"></i> Nombre d'enfants</label>
                    <input type="number" name="children" min="0" required placeholder="0" class="@error('children') is-invalid @enderror" value="{{ old('children', 0) }}">
                </div>
                <div class="form-group">
                    <label><i class="bi bi-person-heart"></i> Ayant droit</label>
                    <input type="text" name="heirs" required placeholder="Nom de l'ayant droit" class="@error('heirs') is-invalid @enderror" value="{{ old('heirs') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="bi bi-envelope"></i> Email</label>
                    <input type="email" name="email" required placeholder="exemple@email.com" class="@error('email') is-invalid @enderror" value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label><i class="bi bi-telephone"></i> Téléphone</label>
                    <input type="text" name="phone" required placeholder="Numéro de téléphone" class="@error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                </div>
            </div>

            <div class="form-group">
                <label><i class="bi bi-cash"></i> Salaire mensuel</label>
                <input type="text" name="salary" required placeholder="Montant en FCFA" class="@error('salary') is-invalid @enderror" value="{{ old('salary') }}" oninput="formatInputMontant(this)">
            </div>

            <div class="form-group">
                <label>Situation matrimoniale:</label>
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="radio" name="maritalStatus" value="Célibataire" id="cel" {{ old('maritalStatus') == 'Célibataire' ? 'checked' : '' }} onchange="toggleConjoint()">
                        <label for="cel">Célibataire</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="radio" name="maritalStatus" value="Marié(e)" id="mar" {{ old('maritalStatus') == 'Marié(e)' ? 'checked' : '' }} onchange="toggleConjoint()">
                        <label for="mar">Marié(e)</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="radio" name="maritalStatus" value="Concubinage" id="conc" {{ old('maritalStatus') == 'Concubinage' ? 'checked' : '' }} onchange="toggleConjoint()">
                        <label for="conc">Concubinage</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="radio" name="maritalStatus" value="Divorcé(e)" id="div" {{ old('maritalStatus') == 'Divorcé(e)' ? 'checked' : '' }} onchange="toggleConjoint()">
                        <label for="div">Divorcé(e)</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="radio" name="maritalStatus" value="Veuf(ve)" id="veuf" {{ old('maritalStatus') == 'Veuf(ve)' ? 'checked' : '' }} onchange="toggleConjoint()">
                        <label for="veuf">Veuf(ve)</label>
                    </div>
                </div>
            </div>

            <div id="conjointFields" style="display: none; background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 15px; border-left: 4px solid #2c5f8d;">
                <h4 style="font-size: 16px; color: #2c3e50; margin-bottom: 15px;">Informations du conjoint</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="bi bi-person"></i> Nom du conjoint <span style="color: red">*</span></label>
                        <input type="text" name="nomConjoint" id="nomConjoint" placeholder="Nom complet du conjoint" value="{{ old('nomConjoint') }}">
                    </div>
                    <div class="form-group">
                        <label><i class="bi bi-telephone"></i> Téléphone du conjoint <span style="color: red">*</span></label>
                        <input type="text" name="telephoneConjoint" id="telephoneConjoint" placeholder="Numéro de téléphone" value="{{ old('telephoneConjoint') }}">
                    </div>
                </div>
            </div>

            <div class="buttons">
                <button type="button" class="btn-secondary" onclick="prevStep()">Retour</button>
                <button type="button" class="btn-primary" onclick="nextStep()">Continuer</button>
            </div>
        </div>

        <!-- ÉTAPE 2 -->
        <div class="step" id="step2">
            <h3>Identification et Programme</h3>

            <div class="form-group">
                <label>Nature de la pièce:</label>
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="checkbox" name="idType" value="CNI" id="cni" {{ old('idType') == 'CNI' ? 'checked' : '' }} onclick="uncheckOthers(this, 'idType')">
                        <label for="cni">CNI</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="idType" value="Passeport" id="pass" {{ old('idType') == 'Passeport' ? 'checked' : '' }} onclick="uncheckOthers(this, 'idType')">
                        <label for="pass">Passeport</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="idType" value="Carte consulaire" id="cons" {{ old('idType') == 'Carte consulaire' ? 'checked' : '' }} onclick="uncheckOthers(this, 'idType')">
                        <label for="cons">Carte consulaire</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label><i class="bi bi-file-text"></i> Numéro CNI / Passeport</label>
                <input type="text" name="idNumber" required placeholder="Numéro d'identification" class="@error('idNumber') is-invalid @enderror" value="{{ old('idNumber') }}">
            </div>

            <div class="form-group">
                <label>Téléverser la CNI/Passeport</label>
                <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
                    <div class="file-upload-icon"><i class="bi bi-cloud-upload"></i></div>
                    <div class="file-upload-text">Joindre un document en PDF, JPG, PNG<br>(Taille maximale 10 Mo)</div>
                </div>
                <input type="file" id="fileInput" name="idFile" accept=".pdf,.jpg,.jpeg,.png" style="display:none" onchange="updateFileName(this)">
                <div id="fileName" style="margin-top: 10px; font-size: 13px; color: #27ae60;"></div>
            </div>

            <div class="form-group">
                <label>Programme</label>
                <select name="program" required onchange="chargerBiensImmobiliers()">
                    <option value="">-- Sélectionnez un programme --</option>
                    @foreach($projets as $projet)
                        <option value="{{ $projet->id }}" {{ old('program') == $projet->id ? 'selected' : '' }}>{{ $projet->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>La durée du contrat</label>
                <div class="form-row">
                    <div>
                        <label style="font-size: 12px; color: #888;"><i class="bi bi-calendar"></i> Date de début</label>
                        <input type="date" name="startDate" required value="{{ old('startDate') }}">
                    </div>
                    <div>
                        <label style="font-size: 12px; color: #888;"><i class="bi bi-calendar"></i> Date de fin</label>
                        <input type="date" name="endDate" required value="{{ old('endDate') }}">
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
            <h3>Logement et Financement</h3>

            <div class="form-group">
                <label>Types de logement :</label>
                <div class="housing-options" id="housingOptionsContainer">
                    <div style="padding: 20px; text-align: center; color: #666;">Veuillez d'abord sélectionner un programme</div>
                </div>
                <input type="hidden" name="housingType" id="housingType" required value="{{ old('housingType') }}">
            </div>

            <div class="form-group">
                <label>Mode paiement</label>
                <div class="payment-options">
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'ESPECES')"><div class="option-checkbox"></div><span>ESPECES</span></div>
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'VIREMENT')"><div class="option-checkbox"></div><span>VIREMENT</span></div>
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'PRELEVEMENT_SOURCE')"><div class="option-checkbox"></div><span>PRÉLÈVEMENT À LA SOURCE</span></div>
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'TEMPERAMENT')"><div class="option-checkbox"></div><span>TEMPERAMENT</span></div>
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'CREDIT_BANCAIRE')"><div class="option-checkbox"></div><span>CREDIT_BANCAIRE</span></div>
                </div>
                <input type="hidden" name="paymentMode" id="paymentMode" required value="{{ old('paymentMode') }}">
            </div>

            <div class="summary-box">
                <h4>💰 Valeur de la souscription : <span id="valeurSouscription">-</span> FCFA</h4>
                <div class="summary-line"><span>Apport initial (<span id="pourcentageApport">-</span>%)</span><strong><span id="apportInitial">-</span> FCFA</strong></div>
            </div>

            <div class="warning-box">⚠️ Les frais de souscription s'élèvent à <span id="fraisSouscription">-</span> FCFA (non remboursables)</div>

            <div class="form-group">
                <div class="checkbox-item">
                    <input type="hidden" name="apport_initial_paye_par_client" value="0">
                    <input type="checkbox" id="apport_initial_paye_par_client" name="apport_initial_paye_par_client" value="1" {{ old('apport_initial_paye_par_client', '1') ? 'checked' : '' }}>
                    <label for="apport_initial_paye_par_client">Apport initial à payer par le client</label>
                </div>
            </div>

            <input type="hidden" name="valeur_souscription" id="valeur_souscription_input" value="{{ old('valeur_souscription') }}">
            <input type="hidden" name="apport_initial" id="apport_initial_input" value="{{ old('apport_initial') }}">
            <input type="hidden" name="frais_souscription" id="frais_souscription_input" value="{{ old('frais_souscription') }}">

            <div class="buttons">
                <button type="button" class="btn-secondary" onclick="prevStep()">Retour</button>
                <button type="button" class="btn-primary" onclick="nextStep()">Continuer</button>
            </div>
        </div>

        <!-- ÉTAPE 4 -->
        <div class="step" id="step4">
            <h3>Récapitulatif</h3>
            <div class="recap-box" id="recapContent"></div>
            <div class="form-group">
                <div class="checkbox-item">
                    <input type="checkbox" id="certify" name="certify" required {{ old('certify') ? 'checked' : '' }}>
                    <label for="certify">Je certifie que les informations fournies sont exactes</label>
                </div>
            </div>
            <div class="buttons">
                <button type="button" class="btn-secondary" onclick="prevStep()">Retour</button>
                <button type="submit" class="btn-primary">Enregistrer la souscription</button>
            </div>
        </div>
    </form>

    <div class="success-screen {{ session('success') ? 'active' : '' }}" id="successScreen">
        <div class="success-icon"><i class="bi bi-check-circle"></i></div>
        <h3>Souscription enregistrée avec succès</h3>
        @if(session('fiche_souscription_url'))
            <div style="margin: 20px 0; display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
                <a class="btn-primary" href="{{ session('fiche_souscription_url') }}" target="_blank" style="display:inline-block; text-align:center; text-decoration:none; flex:0 0 auto;">
                    Imprimer la fiche de souscription
                </a>
                @if(session('fiche_souscription_send_url'))
                    <form method="POST" action="{{ session('fiche_souscription_send_url') }}" style="margin:0; flex:0 0 auto;">
                        @csrf
                        <button type="submit" class="btn-secondary">Envoyer au client pour signature</button>
                    </form>
                @endif
            </div>
        @endif
        <a class="btn-primary" href="{{ route('chef_commercial.dashboard') }}" style="display:inline-block; text-align:center; text-decoration:none;">Retour au tableau de bord</a>
    </div>
</div>

<script type="application/json" id="biensImmobiliersData">@php echo json_encode($biensImmobiliers); @endphp</script>
<script>
    let currentStep = 0;
    const totalSteps = 5;
    const biensImmobiliers = JSON.parse(document.getElementById('biensImmobiliersData')?.textContent || '{}');

    function updateProgress() {
        const progress = ((currentStep + 1) / totalSteps) * 100;
        document.getElementById('progressFill').style.width = progress + '%';
        document.getElementById('stepIndicator').textContent = (currentStep + 1) + '/' + totalSteps;
    }

    function showStep(n) {
        document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
        document.getElementById('step' + n).classList.add('active');
        updateProgress();
    }

    function selectCategory(element, value) {
        document.querySelectorAll('.category-card').forEach(c => c.classList.remove('selected'));
        element.classList.add('selected');
        document.getElementById('clientCategory').value = value;
        document.getElementById('continueCategory').disabled = false;
        toggleOrganisationFields();
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

    function uncheckOthers(checkbox, name) {
        const checkboxes = document.getElementsByName(name);
        checkboxes.forEach((item) => {
            if (item !== checkbox) item.checked = false;
        });
    }

    function toggleConjoint() {
        const maritalStatus = document.querySelector('input[name="maritalStatus"]:checked')?.value;
        const conjointFields = document.getElementById('conjointFields');
        const nomConjoint = document.getElementById('nomConjoint');
        const telephoneConjoint = document.getElementById('telephoneConjoint');
        
        if (maritalStatus === 'Marié(e)') {
            conjointFields.style.display = 'block';
            nomConjoint.setAttribute('required', 'required');
            telephoneConjoint.setAttribute('required', 'required');
        } else {
            conjointFields.style.display = 'none';
            nomConjoint.removeAttribute('required');
            telephoneConjoint.removeAttribute('required');
        }
    }

    function chargerBiensImmobiliers() {
        const projetId = document.querySelector('select[name="program"]').value;
        const housingContainer = document.getElementById('housingOptionsContainer');
        const housingTypeInput = document.getElementById('housingType');
        
        housingContainer.innerHTML = '';
        housingTypeInput.value = '';
        
        const mutSel = document.getElementById('mutuelleSelect');
        if (mutSel) {
            const opts = mutSel.querySelectorAll('option');
            opts.forEach(o => {
                const pid = o.getAttribute('data-project');
                if (!o.value) return;
                if (pid && String(pid) !== String(projetId)) {
                    o.style.display = 'none';
                    if (mutSel.value === o.value) mutSel.value = '';
                } else {
                    o.style.display = '';
                }
            });
        }

        if (!projetId || !biensImmobiliers[projetId]) {
            housingContainer.innerHTML = '<div style="padding: 20px; text-align: center; color: #666;">Aucun bien disponible</div>';
            return;
        }
        
        biensImmobiliers[projetId].forEach(bien => {
            const card = document.createElement('div');
            card.className = 'option-card';
            card.onclick = function() {
                selectOption(this, 'housingType', bien.id + '|' + bien.titre);
                calculerValeurs(bien);
            };
            card.innerHTML = `
                <div class="option-checkbox"></div>
                <div>
                    <strong>${bien.titre}</strong><br>
                    <small>${formatMontant(bien.prix)} FCFA</small>
                </div>
            `;
            housingContainer.appendChild(card);
        });
    }

    function calculerValeurs(bien) {
        let valeurSouscription = parseFloat(bien.prix) || 0;
        const mutuelleSel = document.getElementById('mutuelleSelect');
        const orgType = (document.querySelector('input[name="organisation_type"]:checked')?.value || '').toLowerCase();
        if (orgType === 'mutuelle' && mutuelleSel && mutuelleSel.value && Array.isArray(bien.mutuelles)) {
            const m = bien.mutuelles.find(x => String(x.id) === String(mutuelleSel.value));
            if (m && m.pivot && m.pivot.prix_special) {
                valeurSouscription = parseFloat(m.pivot.prix_special);
            }
        }
        const pourcentage = parseFloat(bien.pourcentage_apport) || 10;
        const apportCalc = parseFloat(bien.apport_initial) || Math.round(valeurSouscription * (pourcentage / 100));
        const frais = parseFloat(bien.frais_souscription) || 500000;
        const apportPaye = document.getElementById('apport_initial_paye_par_client')?.checked ?? true;
        const apport = apportPaye ? apportCalc : 0;
        
        document.getElementById('valeurSouscription').textContent = formatMontant(valeurSouscription);
        document.getElementById('pourcentageApport').textContent = apportPaye ? pourcentage : 0;
        document.getElementById('apportInitial').textContent = formatMontant(apport);
        document.getElementById('fraisSouscription').textContent = formatMontant(frais);
        
        document.getElementById('valeur_souscription_input').value = valeurSouscription;
        document.getElementById('apport_initial_input').value = apport;
        document.getElementById('frais_souscription_input').value = frais;
    }

    function formatMontant(m) {
        return new Intl.NumberFormat('fr-FR').format(m).replace(/\u202f/g, ' ');
    }

    function formatInputMontant(input) {
        let v = input.value.replace(/\D/g, '');
        if (v) input.value = formatMontant(v);
    }

    function nextStep() {
        if (validateStep(currentStep)) {
            if (currentStep === 3) generateRecap();
            currentStep++;
            showStep(currentStep);
        }
    }

    function prevStep() {
        currentStep--;
        showStep(currentStep);
    }

    function validateStep(s) {
        const step = document.getElementById('step' + s);
        const required = step.querySelectorAll('[required]');
        const processedRadioNames = new Set();
        for (let f of required) {
            const type = (f.getAttribute('type') || '').toLowerCase();
            if (type === 'radio') {
                const name = f.getAttribute('name') || '';
                if (!name || processedRadioNames.has(name)) continue;
                processedRadioNames.add(name);
                const checked = step.querySelector('input[type="radio"][name="' + name + '"]:checked');
                if (!checked) {
                    alert('Veuillez remplir tous les champs obligatoires');
                    f.focus();
                    return false;
                }
                continue;
            }

            if (type === 'checkbox') {
                if (!f.checked) {
                    alert('Veuillez remplir tous les champs obligatoires');
                    f.focus();
                    return false;
                }
                continue;
            }

            if (!String(f.value || '').trim()) {
                alert('Veuillez remplir tous les champs obligatoires');
                f.focus();
                return false;
            }
        }
        return true;
    }

    function generateRecap() {
        const form = document.getElementById('subscriptionForm');
        const data = new FormData(form);
        let html = '';

        html += `<div class="recap-item"><strong>Catégorie:</strong> ${data.get('clientCategory') || '-'}</div>`;
        if ((data.get('clientCategory') || '').toLowerCase() === 'association syndicat mutuelle') {
            html += `<div class="recap-item"><strong>Organisation:</strong> ${data.get('organisation_type') || '-'}</div>`;
        }
        const catLower = (data.get('clientCategory') || '').toLowerCase();
        const orgLower = (data.get('organisation_type') || '').toLowerCase();
        if (orgLower === 'mutuelle' || catLower === 'mutuelle') {
            const mutSel = document.getElementById('mutuelleSelect');
            const mutText = (mutSel && mutSel.selectedIndex > 0) ? mutSel.options[mutSel.selectedIndex].text : '-';
            html += `<div class="recap-item"><strong>Mutuelle:</strong> ${mutText}</div>`;
        }
        html += `<div class="recap-item"><strong>Nom:</strong> ${data.get('nom') || ''} ${data.get('prenom') || ''}</div>`;
        html += `<div class="recap-item"><strong>Email:</strong> ${data.get('email') || '-'}</div>`;
        html += `<div class="recap-item"><strong>Téléphone:</strong> ${data.get('phone') || '-'}</div>`;
        const housingLabel = (data.get('housingType') || '').includes('|') ? (data.get('housingType') || '').split('|')[1] : (data.get('housingType') || '-');
        html += `<div class="recap-item"><strong>Logement:</strong> ${housingLabel}</div>`;
        html += `<div class="recap-item"><strong>Valeur:</strong> ${formatMontant(data.get('valeur_souscription') || 0)} FCFA</div>`;
        html += `<div class="recap-item"><strong>Apport initial payé:</strong> ${String(data.get('apport_initial_paye_par_client') || '0') === '1' ? 'Oui' : 'Non'}</div>`;
        html += `<div class="recap-item"><strong>Apport initial:</strong> ${formatMontant(data.get('apport_initial') || 0)} FCFA</div>`;
        html += `<div class="recap-item"><strong>Frais souscription:</strong> ${formatMontant(data.get('frais_souscription') || 0)} FCFA</div>`;
        
        document.getElementById('recapContent').innerHTML = html;
    }

    document.addEventListener('DOMContentLoaded', () => {
        showStep(0);
        toggleConjoint();
        toggleOrganisationFields();
        const apportCheckbox = document.getElementById('apport_initial_paye_par_client');
        if (apportCheckbox) {
            apportCheckbox.addEventListener('change', function () {
                const housingValue = document.getElementById('housingType')?.value || '';
                if (!housingValue.includes('|')) return;
                const projetId = document.querySelector('select[name="program"]')?.value;
                const [bienId] = housingValue.split('|');
                const bien = (biensImmobiliers[projetId] || []).find(b => String(b.id) === String(bienId));
                if (bien) calculerValeurs(bien);
            });
        }
        const mutSel = document.getElementById('mutuelleSelect');
        if (mutSel) {
            mutSel.addEventListener('change', function() {
                const housingValue = document.getElementById('housingType')?.value || '';
                if (!housingValue.includes('|')) return;
                const projetId = document.querySelector('select[name="program"]')?.value;
                const [bienId] = housingValue.split('|');
                const bien = (biensImmobiliers[projetId] || []).find(b => String(b.id) === String(bienId));
                if (bien) calculerValeurs(bien);
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

@php
    $storeAction = $storeAction ?? route('dg.souscriptions.store');
    $dashboardUrl = $dashboardUrl ?? route('dg.dashboard');
@endphp
<style>
    .subscription-wizard,
    .subscription-wizard *,
    .subscription-wizard *::before,
    .subscription-wizard *::after {
        box-sizing: border-box;
    }

    .subscription-wizard {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        --wizard-radius: var(--ui-radius, 0.75rem);
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 100%;
        width: 100%;
    }

    .subscription-wizard:not(.subscription-wizard--embedded),
    .subscription-wizard--embedded {
        background: #fff;
        padding: 0;
    }

    .subscription-wizard .header {
        background: white;
        padding: 30px 20px 20px;
        text-align: center;
        border-bottom: 1px solid #e0e0e0;
    }

    .logo {
        width: 120px;
        height: 60px;
        margin: 0 auto 15px;
        background: #111827;
        border-radius: var(--wizard-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
        position: relative;
    }

    .logo::before {
        content: 'DIGIT BTP';
        position: absolute;
        font-size: 14px;
        letter-spacing: 0.5px;
    }

    .logo-subtitle {
        font-size: 9px;
        color: white;
        margin-top: 25px;
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
        background: #ff7200;
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
    .mutuelle-select {
        margin: 10px 0 20px;
        display: none;
    }

    .category-card {
        border: 2px solid #e0e0e0;
        border-radius: var(--wizard-radius);
        padding: 20px 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .category-card:hover {
        border-color: #ff7200;
        background: #f8f9fa;
    }

    .category-card.selected {
        border-color: #ff7200;
        background: #f9fafb;
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
        font-size: 22px;
        color: #64748b;
    }

    .category-icon .bi {
        line-height: 1;
    }

    .category-card.selected .category-icon {
        background: #ff7200;
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

    .subscription-wizard label {
        display: block;
        font-size: 14px;
        color: #555;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .subscription-wizard input:not([type="radio"]):not([type="checkbox"]):not([type="hidden"]):not([type="file"]),
    .subscription-wizard select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: var(--wizard-radius);
        font-size: 14px;
        transition: border-color 0.3s ease;
        background: white;
    }

    .subscription-wizard input:focus:not([type="radio"]):not([type="checkbox"]):not([type="hidden"]),
    .subscription-wizard select:focus {
        outline: none;
        border-color: #ff7200;
    }

    .subscription-wizard input[type="file"] {
        padding: 10px;
        cursor: pointer;
        width: 100%;
    }

    .checkbox-item input[type="radio"],
    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        min-width: 18px;
        padding: 0;
        margin: 0;
        flex-shrink: 0;
        cursor: pointer;
        accent-color: #ff7200;
    }

    .file-upload-area {
        border: 2px dashed #ddd;
        border-radius: var(--wizard-radius);
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .file-upload-area:hover {
        border-color: #ff7200;
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

    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        min-width: 18px;
        cursor: pointer;
    }

    .checkbox-item label {
        margin: 0;
        cursor: pointer;
        font-weight: normal;
    }

    .housing-options, .payment-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 10px;
    }

    .option-card {
        border: 2px solid #e0e0e0;
        border-radius: var(--wizard-radius);
        padding: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .option-card:hover {
        border-color: #ff7200;
        background: #f8f9fa;
    }

    .option-card.selected {
        border-color: #ff7200;
        background: #f9fafb;
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
        background: #ff7200;
        border-color: #ff7200;
        color: white;
    }

    .option-card.selected .option-checkbox::before {
        content: '';
        width: 14px;
        height: 14px;
        background: center / contain no-repeat url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='white' stroke-width='3'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M4.5 12.75l6 6 9-13.5'/%3E%3C/svg%3E");
    }

    .summary-box {
        background: #f8f9fa;
        border-left: 4px solid #ff7200;
        padding: 20px;
        border-radius: var(--wizard-radius);
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

    .summary-line.total {
        font-weight: bold;
        font-size: 16px;
        color: #2c3e50;
        padding-top: 10px;
        border-top: 1px solid #ddd;
        margin-top: 10px;
    }

    .warning-box {
        background: #ffedd5;
        border-left: 4px solid #ff7200;
        padding: 15px;
        border-radius: var(--wizard-radius);
        margin: 20px 0;
        font-size: 13px;
        color: #c2410c;
    }

    .buttons {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    .subscription-wizard button {
        padding: 14px 30px;
        border: none;
        border-radius: var(--wizard-radius);
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        flex: 1;
    }

    .subscription-wizard .btn-secondary {
        background: #ecf0f1;
        color: #555;
    }

    .subscription-wizard .btn-secondary:hover {
        background: #d5dbdb;
    }

    .subscription-wizard .btn-primary {
        background: #ff7200;
        color: white;
    }

    .subscription-wizard .btn-primary:hover {
        background: #e66500;
    }

    .success-screen {
        display: none;
        text-align: center;
        padding: 60px 30px;
    }

    .success-screen.active {
        display: block;
    }

    .success-actions {
        margin: 20px 0 0;
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .success-actions a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        text-decoration: none;
        padding: 14px 30px;
        border-radius: var(--wizard-radius);
        font-size: 15px;
        font-weight: 600;
        min-width: 260px;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 25px;
        background: #6b7280;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        color: white;
    }

    .success-screen h3 {
        color: #2c3e50;
        margin-bottom: 15px;
        font-size: 22px;
    }

    .success-screen p {
        color: #7f8c8d;
        margin-bottom: 30px;
    }

    .recap-box {
        background: #f8f9fa;
        border-radius: var(--wizard-radius);
        padding: 20px;
        text-align: left;
        margin: 30px 0;
        text-align: justify;
    }

    .recap-box h4 {
        margin-bottom: 15px;
        color: #2c3e50;
    }

    .recap-item {
        margin-bottom: 8px;
        font-size: 14px;
        color: #555;
        text-align: justify;
    }

    .recap-item strong {
        color: #2c3e50;
    }

    .recap-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 16px;
    }

    .edit-step {
        background: none;
        border: none;
        color: #ff7200;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .edit-step:hover {
        text-decoration: underline;
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
@include('shared.subscription-wizard.sidebar-styles')

<div class="subscription-wizard {{ !empty($embedded) ? 'subscription-wizard--embedded' : '' }}">
    <div class="wizard-page" id="wizardLayout">
        <x-subscription-wizard-sidebar :dashboard-url="$dashboardUrl" />

        <div class="wizard-main">
    @if (empty($embedded))
    <div class="header">
        <x-org-logo class="max-w-[200px] h-auto mb-2" />
        <h2>Nouvelle souscription</h2>
        <p>Promoteur immobilier agréé</p>
    </div>
    @endif

        <div class="wizard-content">
    <div class="progress-bar" @if (session('success')) style="display:none" @endif>
        <div class="progress-fill" id="progressFill" style="width: 20%"></div>
    </div>

    <div class="step-indicator" id="stepIndicator" @if (session('success')) style="display:none" @endif>1/5</div>

    <form id="subscriptionForm" action="{{ $storeAction }}" method="POST" enctype="multipart/form-data" novalidate @if (session('success')) style="display:none" @endif>
        @csrf

        <!-- Messages d'erreur -->
        @if ($errors->any())
            <div class="alert alert-danger" style="margin: 20px 30px; padding: 15px; border-radius: var(--ui-radius, 0.75rem); background-color: #ffedd5; border: 1px solid #fed7aa; color: #c2410c;">
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
                <select name="mutuelle_id" id="mutuelleSelect">
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
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="bi bi-person"></i> Nom</label>
                        <input type="text" name="nom" required placeholder="Nom" class="@error('nom') is-invalid @enderror" value="{{ old('nom') }}">
                        @error('nom')
                            <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label><i class="bi bi-person"></i> Prénom</label>
                        <input type="text" name="prenom" required placeholder="Prénom" class="@error('prenom') is-invalid @enderror" value="{{ old('prenom') }}">
                        @error('prenom')
                            <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="bi bi-calendar"></i> Date de naissance</label>
                    <input type="date" name="birthDate" required class="@error('birthDate') is-invalid @enderror" value="{{ old('birthDate') }}">
                    @error('birthDate')
                        <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="bi bi-geo-alt"></i> Lieu de naissance</label>
                    <input type="text" name="birthPlace" required placeholder="Ville" class="@error('birthPlace') is-invalid @enderror" value="{{ old('birthPlace') }}">
                    @error('birthPlace')
                        <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label><i class="bi bi-globe"></i> Nationalité</label>
                    <input type="text" name="nationality" required placeholder="Nationalité" class="@error('nationality') is-invalid @enderror" value="{{ old('nationality') }}">
                    @error('nationality')
                        <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="bi bi-person-plus"></i> Nombre d'enfants</label>
                    <input type="number" name="children" min="0" required placeholder="0" class="@error('children') is-invalid @enderror" value="{{ old('children') }}">
                    @error('children')
                        <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label><i class="bi bi-person-heart"></i> Ayant droit</label>
                    <input type="text" name="heirs" required placeholder="Nom de l'ayant droit" class="@error('heirs') is-invalid @enderror" value="{{ old('heirs') }}">
                    @error('heirs')
                        <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="bi bi-envelope"></i> Email</label>
                    <input type="email" name="email" required placeholder="exemple@email.com" class="@error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label><i class="bi bi-telephone"></i> Téléphone</label>
                    <input type="text" name="phone" required placeholder="Numéro de téléphone" class="@error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label><i class="bi bi-cash"></i> Salaire mensuel</label>
                <input type="text" name="salary" required placeholder="Montant en FCFA" class="format-number @error('salary') is-invalid @enderror" value="{{ old('salary') }}" oninput="formatInput(this)">
                @error('salary')
                    <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Profession</label>
                    <input type="text" name="profession" placeholder="Profession" value="{{ old('profession') }}">
                </div>
                <div class="form-group">
                    <label>Nom de l'entreprise</label>
                    <input type="text" name="entreprise" placeholder="Employeur / structure" value="{{ old('entreprise') }}">
                </div>
            </div>
            <div class="form-group">
                <label>Lieu de résidence actuel</label>
                <input type="text" name="lieu_residence" placeholder="Adresse complète" value="{{ old('lieu_residence') }}">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Ville</label>
                    <input type="text" name="ville" placeholder="Ville" value="{{ old('ville') }}">
                </div>
                <div class="form-group">
                    <label>Pays</label>
                    <input type="text" name="pays" placeholder="Pays" value="{{ old('pays') }}">
                </div>
            </div>

            <div class="form-group">
                <label>Situation matrimoniale:</label>
                <div class="checkbox-group @error('maritalStatus') is-invalid @enderror">
                    <div class="checkbox-item">
                        <input type="radio" name="maritalStatus" value="Célibataire" id="cel" {{ old('maritalStatus') == 'Célibataire' ? 'checked' : '' }} onchange="toggleConjoint(this)">
                        <label for="cel">Célibataire</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="radio" name="maritalStatus" value="Marié(e)" id="mar" {{ old('maritalStatus') == 'Marié(e)' ? 'checked' : '' }} onchange="toggleConjoint(this)">
                        <label for="mar">Marié(e)</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="radio" name="maritalStatus" value="Concubinage" id="conc" {{ old('maritalStatus') == 'Concubinage' ? 'checked' : '' }} onchange="toggleConjoint(this)">
                        <label for="conc">Concubinage</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="radio" name="maritalStatus" value="Divorcé(e)" id="div" {{ old('maritalStatus') == 'Divorcé(e)' ? 'checked' : '' }} onchange="toggleConjoint(this)">
                        <label for="div">Divorcé(e)</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="radio" name="maritalStatus" value="Veuf(ve)" id="veuf" {{ old('maritalStatus') == 'Veuf(ve)' ? 'checked' : '' }} onchange="toggleConjoint(this)">
                        <label for="veuf">Veuf(ve)</label>
                    </div>
                </div>
                @error('maritalStatus')
                    <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div id="conjointFields" style="display: none; background: #f8f9fa; padding: 15px; border-radius: var(--ui-radius, 0.75rem); border-left: 3px solid #ff7200; margin-top: 15px;">
                <h4 style="font-size: 14px; color: #ff7200; margin-bottom: 10px;">Informations du conjoint</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="bi bi-person"></i> Nom du conjoint</label>
                        <input type="text" name="nomConjoint" id="nomConjoint" placeholder="Nom complet du conjoint" value="{{ old('nomConjoint') }}">
                    </div>
                    <div class="form-group">
                        <label><i class="bi bi-telephone"></i> Téléphone du conjoint</label>
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
                <div class="checkbox-group @error('idType') is-invalid @enderror">
                    <div class="checkbox-item">
                        <input type="checkbox" name="idType" value="CNI" id="cni" {{ old('idType') == 'CNI' ? 'checked' : '' }}>
                        <label for="cni">CNI</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="idType" value="Passeport" id="pass" {{ old('idType') == 'Passeport' ? 'checked' : '' }}>
                        <label for="pass">Passeport</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="idType" value="Carte consulaire" id="cons" {{ old('idType') == 'Carte consulaire' ? 'checked' : '' }}>
                        <label for="cons">Carte consulaire</label>
                    </div>
                </div>
                @error('idType')
                    <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label><i class="bi bi-file-text"></i> Numéro CNI / Passeport</label>
                <input type="text" name="idNumber" required placeholder="Numéro d'identification" class="@error('idNumber') is-invalid @enderror" value="{{ old('idNumber') }}">
                @error('idNumber')
                    <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Date de délivrance de la pièce</label>
                    <input type="date" name="date_delivrance_piece" value="{{ old('date_delivrance_piece') }}">
                </div>
                <div class="form-group">
                    <label>Date d'expiration de la pièce</label>
                    <input type="date" name="date_expiration_piece" value="{{ old('date_expiration_piece') }}">
                </div>
            </div>

            <div class="form-group">
                <label>Téléverser la CNI/Passeport</label>
                <div class="file-upload-area @error('idFile') is-invalid @enderror" onclick="document.getElementById('fileInput').click()">
                    <div class="file-upload-icon"><i class="bi bi-cloud-upload"></i></div>
                    <div class="file-upload-text">
                        Joindre un document en PDF, JPG, PNG<br>
                        (Taille maximale 10 Mo)
                    </div>
                </div>
                <input type="file" id="fileInput" name="idFile" accept=".pdf,.jpg,.jpeg,.png" style="display:none" onchange="updateFileName(this)" class="@error('idFile') is-invalid @enderror">
                <div id="fileName" style="margin-top: 10px; font-size: 13px; color: #374151;"></div>
                <div id="filePreviewContainer" style="margin-top: 12px; display: none;">
                    <div id="imagePreviewWrapper" style="display:none;">
                        <img id="imagePreview" alt="Aperçu" style="max-width: 100%; max-height: 320px; border: 1px solid #eee; border-radius: var(--ui-radius, 0.75rem);">
                    </div>
                    <div id="pdfPreviewWrapper" style="display:none; margin-top: 8px;">
                        <embed id="pdfPreview" type="application/pdf" style="width: 100%; height: 420px; border: 1px solid #eee; border-radius: var(--ui-radius, 0.75rem);">
                    </div>
                    <div style="margin-top: 8px;">
                        <a id="downloadPreview" href="#" target="_blank" style="display:none; font-size: 12px;">Ouvrir le fichier dans un nouvel onglet</a>
                    </div>
                </div>
                @error('idFile')
                    <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Programme</label>
                <select name="program" required class="@error('program') is-invalid @enderror">
                    <option value="">-- Sélectionnez un programme --</option>
                    @foreach($projets as $projet)
                        <option value="{{ $projet->id }}" 
                                data-prix-terrain="{{ $projet->prix_terrains }}"
                                data-prix-duplex="{{ $projet->prix_duplex }}"
                                data-prix-villa="{{ $projet->prix_villa }}"
                                data-prix-appartement="{{ $projet->prix_appartement }}"
                                data-pourcentage-apport="{{ $projet->pourcentage_apport }}"
                                data-frais-souscription="{{ $projet->frais_souscription }}"
                                {{ old('program') == $projet->id ? 'selected' : '' }}>
                            {{ $projet->nom }}
                        </option>
                    @endforeach
                </select>
                @error('program')
                    <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>La durée du contrat</label>
                <div class="form-row">
                    <div>
                        <label style="font-size: 12px; color: #888;"><i class="bi bi-calendar"></i> Date de début</label>
                        <input type="date" name="startDate" required class="@error('startDate') is-invalid @enderror" value="{{ old('startDate') }}">
                        @error('startDate')
                            <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 12px; color: #888;"><i class="bi bi-calendar"></i> Date de fin</label>
                        <input type="date" name="endDate" required class="@error('endDate') is-invalid @enderror" value="{{ old('endDate') }}">
                        @error('endDate')
                            <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
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
                <div class="housing-options @error('housingType') is-invalid @enderror" id="housingOptionsContainer">
                    <!-- Les options seront chargées dynamiquement -->
                </div>
                <input type="hidden" name="housingType" id="housingType" required class="@error('housingType') is-invalid @enderror" value="{{ old('housingType') }}">
                @error('housingType')
                    <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Mode paiement</label>
                <div class="payment-options @error('paymentMode') is-invalid @enderror">
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'ESPECES')">
                        <div class="option-checkbox"></div>
                        <span>ESPECES</span>
                    </div>
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'VIREMENT')">
                        <div class="option-checkbox"></div>
                        <span>VIREMENT</span>
                    </div>
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'PRELEVEMENT_SOURCE')">
                        <div class="option-checkbox"></div>
                        <span>PRÉLÈVEMENT À LA SOURCE</span>
                    </div>
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'TEMPERAMENT')">
                        <div class="option-checkbox"></div>
                        <span>TEMPERAMENT</span>
                    </div>
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'CREDIT_BANCAIRE')">
                        <div class="option-checkbox"></div>
                        <span>CREDIT_BANCAIRE</span>
                    </div>
                </div>
                <input type="hidden" name="paymentMode" id="paymentMode" required class="@error('paymentMode') is-invalid @enderror" value="{{ old('paymentMode') }}">
                @error('paymentMode')
                    <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="summary-box">
                <h4 class="flex items-center gap-2"><x-icon name="banknotes" class="h-5 w-5 shrink-0" /> Valeur de la souscription : <span id="valeurSouscription">-</span> FCFA</h4>
                <div class="summary-line">
                    <span>Apport initial (<span id="pourcentageApport">-</span>%)</span>
                    <strong><span id="apportInitial">-</span> FCFA</strong>
                </div>
            </div>

            <div class="warning-box flex items-start gap-2">
                <x-icon name="warning" class="mt-0.5 h-5 w-5 shrink-0" />
                <span>Les frais de souscription s'élèvent à <span id="fraisSouscription">-</span> FCFA (non remboursables)</span>
            </div>

            <div class="form-group">
                <div class="checkbox-item">
                    <input type="hidden" name="apport_initial_paye_par_client" value="0">
                    <input type="checkbox" id="apport_initial_paye_par_client" name="apport_initial_paye_par_client" value="1" checked>
                    <label for="apport_initial_paye_par_client">Apport initial à payer par le client</label>
                </div>
            </div>
            
            <input type="hidden" name="valeur_souscription" id="valeur_souscription_input" value="{{ old('valeur_souscription', '30000000') }}">
            <input type="hidden" name="apport_initial" id="apport_initial_input" value="{{ old('apport_initial', '3000000') }}">
            <input type="hidden" name="frais_souscription" id="frais_souscription_input" value="{{ old('frais_souscription', '500000') }}">

            <div class="buttons">
                <button type="button" class="btn-secondary" onclick="prevStep()">Retour</button>
                <button type="button" class="btn-primary" onclick="nextStep()">Continuer</button>
            </div>
        </div>

        <!-- ÉTAPE 4: Récapitulatif -->
        <div class="step" id="step4">
            <h3>Récapitulatif de la fiche de souscription</h3>

            <div class="recap-box" id="recapContent">
                <!-- Le contenu sera généré dynamiquement -->
            </div>

            <div class="form-group">
                <div class="checkbox-item @error('certify') is-invalid @enderror">
                    <input type="checkbox" id="certify" name="certify" required class="@error('certify') is-invalid @enderror" {{ old('certify') ? 'checked' : '' }}>
                    <label for="certify">Je certifie que les informations fournies sont exactes et l'autorise leur traitement</label>
                </div>
                @error('certify')
                    <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="buttons">
                <button type="button" class="btn-secondary" onclick="prevStep()">Retour</button>
                <button type="submit" class="btn-primary">Enregistrer la souscription</button>
            </div>
        </div>

    </form>

    <!-- Écran de succès -->
    <div class="success-screen {{ session('success') ? 'active' : '' }}" id="successScreen">
        <div class="success-icon"><i class="bi bi-check-circle"></i></div>
        <h3>Votre souscription a été soumise à la comptabilité</h3>

        
        @if(session('client_credentials'))
            <div style="margin: 30px 0; padding: 25px; background: #f9fafb; border: 2px solid #111827; border-radius: var(--ui-radius, 0.75rem); text-align: left;">
                <h4 style="color: #111827; margin-bottom: 15px; font-size: 18px; text-align: center;">
                    <i class="bi bi-key"></i> Compte client crÃ©Ã© avec succÃ¨s
                </h4>
                <p style="color: #555; margin-bottom: 20px; text-align: center; font-size: 14px;">
                    Un compte a Ã©tÃ© automatiquement crÃ©Ã© pour : <strong>{{ session('client_credentials')['nom_client'] }}</strong>
                </p>
                <div style="background: white; padding: 20px; border-radius: var(--ui-radius, 0.75rem); box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-weight: 600; color: #333; margin-bottom: 5px; font-size: 13px;">
                            <i class="bi bi-person-badge"></i> RÃ©fÃ©rence Client:
                        </label>
                        <input type="text" value="{{ session('client_credentials')['ref_client'] }}" readonly 
                               style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: var(--ui-radius, 0.75rem); background: #f8f9fa; font-family: monospace; font-size: 14px;"
                               onclick="this.select()">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-weight: 600; color: #333; margin-bottom: 5px; font-size: 13px;">
                            <i class="bi bi-envelope"></i> Email / Identifiant:
                        </label>
                        <input type="text" value="{{ session('client_credentials')['email'] }}" readonly 
                               style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: var(--ui-radius, 0.75rem); background: #f8f9fa; font-family: monospace; font-size: 14px;"
                               onclick="this.select()">
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label style="display: block; font-weight: 600; color: #333; margin-bottom: 5px; font-size: 13px;">
                            <i class="bi bi-lock"></i> Mot de passe temporaire:
                        </label>
                        <input type="text" value="{{ session('client_credentials')['password'] }}" readonly 
                               style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: var(--ui-radius, 0.75rem); background: #f1f5f9; font-family: monospace; font-size: 16px; font-weight: 600; color: #111827;"
                               onclick="this.select()">
                    </div>
                    <p style="font-size: 12px; color: #475569; margin-top: 15px; padding: 10px; background: #f1f5f9; border-left: 4px solid #111827; border-radius: 4px;">
                        <i class="bi bi-exclamation-triangle"></i> <strong>Important :</strong> Veuillez noter ces informations et les communiquer au client. 
                        Le client devra changer son mot de passe lors de sa premiÃ¨re connexion.
                    </p>
                </div>
            </div>
        @endif
        @if(session('fiche_souscription_url'))
            <div class="success-actions">
                <a class="btn-primary" href="{{ session('fiche_souscription_url') }}" target="_blank">
                    Imprimer la fiche de souscription
                </a>
                @if(session('contrat_reservation_url'))
                    <a class="btn-primary" href="{{ session('contrat_reservation_url') }}" target="_blank">
                        Imprimer le contrat
                    </a>
                @endif
                <a class="btn-secondary" href="{{ $dashboardUrl }}">Retour à la page d’accueil</a>
            </div>
        @endif
        @if(!session('fiche_souscription_url'))
            <div class="success-actions">
                <a class="btn-secondary" href="{{ $dashboardUrl }}">Retour à la page d’accueil</a>
            </div>
        @endif
    </div>

        </div>{{-- .wizard-content --}}
        </div>{{-- .wizard-main --}}
    </div>{{-- .wizard-page --}}
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
        const step = document.getElementById('step' + n);
        if (step) {
            step.classList.add('active');
        }
        updateProgress();
        if (typeof updateWizardSidebar === 'function') {
            updateWizardSidebar();
        }
    }
    
    function goToStep(n) {
        currentStep = n;
        showStep(currentStep);
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
        const file = input.files && input.files[0] ? input.files[0] : null;
        const nameDiv = document.getElementById('fileName');
        const previewContainer = document.getElementById('filePreviewContainer');
        const imgWrapper = document.getElementById('imagePreviewWrapper');
        const pdfWrapper = document.getElementById('pdfPreviewWrapper');
        const imgEl = document.getElementById('imagePreview');
        const pdfEl = document.getElementById('pdfPreview');
        const downloadLink = document.getElementById('downloadPreview');

        // Reset
        nameDiv.textContent = '';
        previewContainer.style.display = 'none';
        imgWrapper.style.display = 'none';
        pdfWrapper.style.display = 'none';
        downloadLink.style.display = 'none';

        // Revoke previous URL if any
        if (window.__idFileObjectUrl) {
            URL.revokeObjectURL(window.__idFileObjectUrl);
            window.__idFileObjectUrl = null;
        }

        if (!file) return;

        nameDiv.textContent = 'Fichier sélectionné: ' + (file.name || '');

        // Basic size check (10 MB)
        const MAX_SIZE = 10 * 1024 * 1024;
        if (file.size > MAX_SIZE) {
            alert('Le fichier dépasse 10 Mo. Merci de choisir un fichier plus léger.');
            input.value = '';
            return;
        }

        const objectUrl = URL.createObjectURL(file);
        window.__idFileObjectUrl = objectUrl;

        // Show preview depending on type
        const type = (file.type || '').toLowerCase();
        previewContainer.style.display = 'block';

        if (type.startsWith('image/')) {
            imgEl.src = objectUrl;
            imgWrapper.style.display = 'block';
            downloadLink.href = objectUrl;
            downloadLink.style.display = 'inline';
        } else if (type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf')) {
            pdfEl.src = objectUrl;
            pdfWrapper.style.display = 'block';
            downloadLink.href = objectUrl;
            downloadLink.style.display = 'inline';
        } else {
            // Unknown preview type: offer open link only
            downloadLink.href = objectUrl;
            downloadLink.style.display = 'inline';
        }
    }

    function nextStep() {
        if (validateStep(currentStep)) {
            if (currentStep === 3) {
                generateRecap();
            }
            currentStep++;
            showStep(currentStep);
        }
    }

    function prevStep() {
        currentStep--;
        showStep(currentStep);
    }

    function validateStep(step) {
        const currentStepElement = document.getElementById('step' + step);
        const requiredFields = currentStepElement.querySelectorAll('[required]');
        const processedRadioNames = new Set();
        
        for (let field of requiredFields) {
            const type = (field.getAttribute('type') || '').toLowerCase();
            if (type === 'radio') {
                const name = field.getAttribute('name') || '';
                if (!name || processedRadioNames.has(name)) continue;
                processedRadioNames.add(name);
                const checked = currentStepElement.querySelector('input[type="radio"][name="' + name + '"]:checked');
                if (!checked) {
                    alert('Veuillez remplir tous les champs obligatoires');
                    field.focus();
                    return false;
                }
                continue;
            }

            if (type === 'checkbox') {
                if (!field.checked) {
                    alert('Veuillez remplir tous les champs obligatoires');
                    field.focus();
                    return false;
                }
                continue;
            }

            if (!String(field.value || '').trim()) {
                alert('Veuillez remplir tous les champs obligatoires');
                field.focus();
                return false;
            }
        }
        return true;
    }
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

    function generateRecap() {
        const form = document.getElementById('subscriptionForm');
        const fd = new FormData(form);
        const programSelect = document.querySelector('select[name="program"]');
        const programText = programSelect && programSelect.selectedIndex >= 0 ? programSelect.options[programSelect.selectedIndex].text : '-';
        const idTypeChecked = Array.from(document.querySelectorAll('input[name="idType"]:checked')).map(el => el.value).join(', ');
        const fileEl = document.getElementById('fileInput');
        const fileName = fileEl && fileEl.files && fileEl.files[0] ? fileEl.files[0].name : 'Non fourni';
        const housingTypeRaw = fd.get('housingType') || '';
        const housingTypeLabel = housingTypeRaw.includes('|') ? housingTypeRaw.split('|')[1] : housingTypeRaw || '-';
        const valeurSous = fd.get('valeur_souscription') ? formatMontant(parseInt(fd.get('valeur_souscription'))) : '-';
        const apportInit = fd.get('apport_initial') ? formatMontant(parseInt(fd.get('apport_initial'))) : '-';
        const fraisSous = fd.get('frais_souscription') ? formatMontant(parseInt(fd.get('frais_souscription'))) : '-';

        let html = '';

        html += '<div class="recap-title"><h4>Catégorie de client</h4><button type="button" class="edit-step" onclick="goToStep(0)"><i class="bi bi-pencil"></i> Modifier</button></div>';
        html += '<div class="recap-item"><strong>Catégorie:</strong> ' + (fd.get('clientCategory') || '-') + '</div>';

        html += '<div class="recap-title"><h4>Informations personnelles</h4><button type="button" class="edit-step" onclick="goToStep(1)"><i class="bi bi-pencil"></i> Modifier</button></div>';
        html += '<div class="recap-item"><strong>Nom:</strong> ' + (fd.get('nom') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Prénom:</strong> ' + (fd.get('prenom') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Date de naissance:</strong> ' + (fd.get('birthDate') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Lieu de naissance:</strong> ' + (fd.get('birthPlace') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Nationalité:</strong> ' + (fd.get('nationality') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Nombre d\'enfants:</strong> ' + (fd.get('children') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Ayant droit:</strong> ' + (fd.get('heirs') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Email:</strong> ' + (fd.get('email') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Téléphone:</strong> ' + (fd.get('phone') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Salaire mensuel:</strong> ' + (fd.get('salary') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Profession:</strong> ' + (fd.get('profession') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Nom de l\'entreprise:</strong> ' + (fd.get('entreprise') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Lieu de résidence actuel:</strong> ' + (fd.get('lieu_residence') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Ville:</strong> ' + (fd.get('ville') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Pays:</strong> ' + (fd.get('pays') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Situation matrimoniale:</strong> ' + (fd.get('maritalStatus') || '-') + '</div>';
        if ((fd.get('maritalStatus') || '') === 'Marié(e)') {
            html += '<div class="recap-item"><strong>Nom du conjoint:</strong> ' + (fd.get('nomConjoint') || '-') + '</div>';
            html += '<div class="recap-item"><strong>Téléphone du conjoint:</strong> ' + (fd.get('telephoneConjoint') || '-') + '</div>';
        }

        html += '<div class="recap-title"><h4>Identification et Programme</h4><button type="button" class="edit-step" onclick="goToStep(2)"><i class="bi bi-pencil"></i> Modifier</button></div>';
        html += '<div class="recap-item"><strong>Nature de la pièce:</strong> ' + (idTypeChecked || '-') + '</div>';
        html += '<div class="recap-item"><strong>Numéro CNI / Passeport:</strong> ' + (fd.get('idNumber') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Date de délivrance de la pièce:</strong> ' + (fd.get('date_delivrance_piece') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Date d\'expiration de la pièce:</strong> ' + (fd.get('date_expiration_piece') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Fichier transmis:</strong> ' + fileName + '</div>';
        const mutSel = document.getElementById('mutuelleSelect');
        const mutVal = (fd.get('mutuelle_id') || (mutSel ? mutSel.value : '') || '').toString();
        const mutOpt = (mutSel && mutVal) ? Array.from(mutSel.options).find(o => String(o.value) === String(mutVal)) : null;
        const mutText = mutOpt ? mutOpt.text : '-';
        if ((fd.get('clientCategory') || '').toLowerCase() === 'association syndicat mutuelle') {
            html += '<div class="recap-item"><strong>Organisation:</strong> ' + (fd.get('organisation_type') || '-') + '</div>';
        }
        const catLower = (fd.get('clientCategory') || '').toLowerCase();
        const orgLower = (fd.get('organisation_type') || '').toLowerCase();
        if (orgLower === 'mutuelle' || catLower === 'mutuelle') {
            html += '<div class="recap-item"><strong>Mutuelle:</strong> ' + mutText + '</div>';
        }
        html += '<div class="recap-item"><strong>Programme:</strong> ' + programText + '</div>';
        html += '<div class="recap-item"><strong>Date de début:</strong> ' + (fd.get('startDate') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Date de fin:</strong> ' + (fd.get('endDate') || '-') + '</div>';

        html += '<div class="recap-title"><h4>Logement et Financement</h4><button type="button" class="edit-step" onclick="goToStep(3)"><i class="bi bi-pencil"></i> Modifier</button></div>';
        html += '<div class="recap-item"><strong>Type de logement:</strong> ' + housingTypeLabel + '</div>';
        html += '<div class="recap-item"><strong>Mode de paiement:</strong> ' + (fd.get('paymentMode') || '-') + '</div>';
        html += '<div class="recap-item"><strong>Valeur de la souscription:</strong> ' + valeurSous + ' FCFA</div>';
        html += '<div class="recap-item"><strong>Apport initial à payer:</strong> ' + ((fd.get('apport_initial_paye_par_client') || '0') === '1' ? 'Oui' : 'Non') + '</div>';
        html += '<div class="recap-item"><strong>Apport initial:</strong> ' + apportInit + ' FCFA</div>';
        html += '<div class="recap-item"><strong>Frais de souscription:</strong> ' + fraisSous + ' FCFA [non remboursables]</div>';

        document.getElementById('recapContent').innerHTML = html;
    }

    document.getElementById('subscriptionForm').addEventListener('submit', function(e) {
        e.preventDefault();

        toggleOrganisationFields();
        
        if (!document.getElementById('certify').checked) {
            alert('Veuillez certifier que les informations sont exactes');
            return;
        }

        const catLower = (document.getElementById('clientCategory')?.value || '').toLowerCase();
        const orgLower = (document.querySelector('input[name="organisation_type"]:checked')?.value || '').toLowerCase();
        const mutWrapper = document.getElementById('mutuelleSelectWrapper');
        const mutSel = document.getElementById('mutuelleSelect');
        const mutuelleSelectIsActive = !!(mutWrapper && mutWrapper.style.display !== 'none' && mutSel && !mutSel.disabled);
        if ((orgLower === 'mutuelle' || catLower === 'mutuelle') && mutuelleSelectIsActive) {
            if (!String(mutSel.value || '').trim()) {
                alert('Veuillez sélectionner une mutuelle');
                goToStep(0);
                setTimeout(function () {
                    toggleOrganisationFields();
                    const m = document.getElementById('mutuelleSelect');
                    if (m) m.focus();
                }, 0);
                return;
            }
        }
        
        // Soumettre le formulaire
        this.submit();
    });

    // Initialize
    showStep(0);
    
    // Restaurer les valeurs sélectionnées après une erreur de validation
    document.addEventListener('DOMContentLoaded', function() {
        initWizardSidebarNav();
        // Restaurer la catégorie sélectionnée
        const clientCategory = document.getElementById('clientCategory').value;
        if (clientCategory) {
            document.querySelectorAll('.category-card').forEach(card => {
                if (card.textContent.includes(clientCategory)) {
                    card.classList.add('selected');
                    document.getElementById('continueCategory').disabled = false;
                }
            });
        }
        toggleOrganisationFields();
        
        // Restaurer le mode de paiement sélectionné
        const paymentMode = document.getElementById('paymentMode').value;
        if (paymentMode) {
            document.querySelectorAll('.payment-options .option-card').forEach(card => {
                if (card.textContent.includes(paymentMode)) {
                    card.classList.add('selected');
                }
            });
        }
        
        // Restaurer le type de logement sélectionné
        const housingType = document.getElementById('housingType').value;
        if (housingType) {
            // Attendre que les biens soient chargés puis restaurer la sélection
            setTimeout(function() {
                const programmeSelect = document.querySelector('select[name="program"]');
                if (programmeSelect && programmeSelect.value) {
                    chargerBiensImmobiliers();
                    // Attendre un peu plus pour que les options soient créées
                    setTimeout(function() {
                        const housingOptions = document.querySelectorAll('.housing-options .option-card');
                        housingOptions.forEach(card => {
                            const cardText = card.textContent;
                            if (housingType.includes('|')) {
                                const [bienId, bienNom] = housingType.split('|');
                                if (cardText.includes(bienNom)) {
                                    card.classList.add('selected');
                                }
                            }
                        });
                    }, 500);
                }
            }, 100);
        }
        
        // Restaurer l'étape active en cas d'erreur
        const errors = document.querySelectorAll('.is-invalid');
        if (errors.length > 0) {
            // Trouver la première erreur et aller à l'étape correspondante
            const firstError = errors[0];
            const stepElement = firstError.closest('.step');
            if (stepElement) {
                const stepId = stepElement.id;
                const stepNumber = parseInt(stepId.replace('step', ''));
                if (!isNaN(stepNumber)) {
                    currentStep = stepNumber;
                    showStep(currentStep);
                }
            }
        }

        const apportCheckbox = document.getElementById('apport_initial_paye_par_client');
        if (apportCheckbox) {
            apportCheckbox.addEventListener('change', function () {
                calculerValeursSouscription();
            });
        }
    });
    
    function formatMontant(montant) {
        if (!montant && montant !== 0) return '';
        return new Intl.NumberFormat('fr-FR').format(montant).replace(/\u202f/g, ' '); 
    }

    // Format input as user types
    function formatInput(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            input.value = formatMontant(parseInt(value));
        } else {
            input.value = '';
        }
    }

    function toggleConjoint(element) {
        const value = element.value;
        const conjointFields = document.getElementById('conjointFields');
        const nomConjoint = document.getElementById('nomConjoint');
        const telephoneConjoint = document.getElementById('telephoneConjoint');
        
        if (value === 'Marié(e)') {
            conjointFields.style.display = 'block';
            nomConjoint.setAttribute('required', 'required');
            telephoneConjoint.setAttribute('required', 'required');
        } else {
            conjointFields.style.display = 'none';
            nomConjoint.removeAttribute('required');
            telephoneConjoint.removeAttribute('required');
        }
    }

    // Clean numbers before submit
    document.getElementById('subscriptionForm').addEventListener('submit', function(e) {
        e.preventDefault();

        toggleOrganisationFields();
        
        if (!document.getElementById('certify').checked) {
            alert('Veuillez certifier que les informations sont exactes');
            return;
        }

        const catLower = (document.getElementById('clientCategory')?.value || '').toLowerCase();
        const orgLower = (document.querySelector('input[name="organisation_type"]:checked')?.value || '').toLowerCase();
        const mutWrapper = document.getElementById('mutuelleSelectWrapper');
        const mutSel = document.getElementById('mutuelleSelect');
        const mutuelleSelectIsActive = !!(mutWrapper && mutWrapper.style.display !== 'none' && mutSel && !mutSel.disabled);
        if ((orgLower === 'mutuelle' || catLower === 'mutuelle') && mutuelleSelectIsActive) {
            if (!String(mutSel.value || '').trim()) {
                alert('Veuillez sélectionner une mutuelle');
                goToStep(0);
                setTimeout(function () {
                    toggleOrganisationFields();
                    const m = document.getElementById('mutuelleSelect');
                    if (m) m.focus();
                }, 0);
                return;
            }
        }

        // Clean salary input
        const salaryInput = document.querySelector('input[name="salary"]');
        if (salaryInput) {
            salaryInput.value = salaryInput.value.replace(/[^\d]/g, '');
        }
        
        // Soumettre le formulaire
        this.submit();
    });

    // Check initial state of conjoint fields
    document.addEventListener('DOMContentLoaded', function() {
        const checkedRadio = document.querySelector('input[name="maritalStatus"]:checked');
        if (checkedRadio) {
            toggleConjoint(checkedRadio);
        }
    });
    function calculerValeursSouscription() {
        const programmeSelect = document.querySelector('select[name="program"]');
        const housingTypeValue = document.getElementById('housingType').value;
        
        if (!programmeSelect.value || !housingTypeValue) {
            return; // Pas assez d'informations pour calculer
        }
        
        // Extraire l'ID du bien immobilier de la valeur
        const [bienId, bienNom] = housingTypeValue.split('|');
        const projetId = programmeSelect.value;
        
        // Trouver le bien immobilier dans le tableau
        const biens = biensImmobiliers[projetId] || [];
        const bien = biens.find(b => b.id == bienId);
        
        if (bien && bien.prix) {
            let valeurSouscription = bien.prix;
            // Si mutuelle sélectionnée et prix spécial disponible sur le pivot, l'appliquer
            const mutuelleSel = document.getElementById('mutuelleSelect');
            const orgType = (document.querySelector('input[name="organisation_type"]:checked')?.value || '').toLowerCase();
            if (orgType === 'mutuelle' && mutuelleSel && mutuelleSel.value && Array.isArray(bien.mutuelles)) {
                const m = bien.mutuelles.find(x => String(x.id) === String(mutuelleSel.value));
                if (m && m.pivot && m.pivot.prix_special) {
                    valeurSouscription = parseFloat(m.pivot.prix_special);
                }
            }
            const pourcentageApport = parseFloat(bien.pourcentage_apport) || 10;
            const fraisSouscription = parseFloat(bien.frais_souscription) || 500000;
            const apportInitialCalc = parseFloat(bien.apport_initial) || Math.round(valeurSouscription * (pourcentageApport / 100));
            const apportPaye = document.getElementById('apport_initial_paye_par_client')?.checked ?? true;
            const apportInitial = apportPaye ? apportInitialCalc : 0;
            
            // Mettre à jour l'affichage
            document.getElementById('valeurSouscription').textContent = formatMontant(valeurSouscription);
            document.getElementById('pourcentageApport').textContent = apportPaye ? pourcentageApport : 0;
            document.getElementById('apportInitial').textContent = formatMontant(apportInitial);
            document.getElementById('fraisSouscription').textContent = formatMontant(fraisSouscription);
            
            // Mettre à jour les champs cachés
            document.getElementById('valeur_souscription_input').value = valeurSouscription;
            document.getElementById('apport_initial_input').value = apportInitial;
            document.getElementById('frais_souscription_input').value = fraisSouscription;
        }
    }
    
    // Charger les biens immobiliers selon le programme sélectionné
    function chargerBiensImmobiliers() {
        const programmeSelect = document.querySelector('select[name="program"]');
        const housingContainer = document.getElementById('housingOptionsContainer');
        const housingTypeInput = document.getElementById('housingType');
        
        if (!programmeSelect.value || !housingContainer) {
            return;
        }
        
        const projetId = programmeSelect.value;
        const biens = biensImmobiliers[projetId] || [];
        // Filtrer les mutuelles selon projet si besoin
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
        
        // Vider le conteneur
        housingContainer.innerHTML = '';
        housingTypeInput.value = '';
        
        if (biens.length === 0) {
            housingContainer.innerHTML = '<div style="padding: 20px; text-align: center; color: #666;">Aucun bien immobilier disponible pour ce programme</div>';
            return;
        }
        
        // Créer les options pour chaque bien immobilier
        biens.forEach(function(bien) {
            const optionCard = document.createElement('div');
            optionCard.className = 'option-card';
            optionCard.onclick = function() {
                selectOption(this, 'housingType', bien.id + '|' + bien.titre);
                calculerValeursSouscription();
            };
            
            optionCard.innerHTML = `
                <div class="option-checkbox"></div>
                <span>${bien.titre}</span>
                <div style="font-size: 12px; color: #666; margin-top: 5px;">
                    Prix: ${formatMontant(bien.prix)} FCFA
                </div>
            `;
            
            housingContainer.appendChild(optionCard);
        });
    }
    
    // Écouter les changements de programme et de type de logement
    document.addEventListener('DOMContentLoaded', function() {
        const programmeSelect = document.querySelector('select[name="program"]');
        
        if (programmeSelect) {
            programmeSelect.addEventListener('change', function() {
                chargerBiensImmobiliers();
                calculerValeursSouscription();
            });
        }
        
            // Charger les biens initiaux si un programme est déjà sélectionné
        if (programmeSelect && programmeSelect.value) {
            chargerBiensImmobiliers();
            // Restaurer la sélection du logement après le chargement
            setTimeout(function() {
                const housingType = document.getElementById('housingType').value;
                if (housingType) {
                    const housingOptions = document.querySelectorAll('.housing-options .option-card');
                    housingOptions.forEach(card => {
                        const cardText = card.textContent;
                        if (housingType.includes('|')) {
                            const [bienId, bienTitre] = housingType.split('|');
                            if (cardText.includes(bienTitre)) {
                                card.classList.add('selected');
                            }
                        }
                    });
                }
            }, 1000);
        }
    });
</script>
@include('shared.subscription-wizard.sidebar-script')


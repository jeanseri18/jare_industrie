@extends('layouts.operateur')

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

    .logo {
        width: 120px;
        height: 60px;
        margin: 0 auto 15px;
        background: linear-gradient(135deg, #c41e3a 0%, #e74c3c 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 24px;
        position: relative;
    }

    .logo::before {
        content: 'JARE';
        position: absolute;
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

    input[type="file"] {
        padding: 10px;
        cursor: pointer;
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

    .checkbox-item input[type="checkbox"] {
        width: 20px;
        height: 20px;
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

    .summary-line.total {
        font-weight: bold;
        font-size: 16px;
        color: #2c3e50;
        padding-top: 10px;
        border-top: 1px solid #ddd;
        margin-top: 10px;
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
        border-radius: 6px;
        padding: 20px;
        text-align: left;
        margin: 30px 0;
    }

    .recap-box h4 {
        margin-bottom: 15px;
        color: #2c3e50;
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

    <div class="progress-bar">
        <div class="progress-fill" id="progressFill" style="width: 20%"></div>
    </div>

    <div class="step-indicator" id="stepIndicator">1/5</div>

    <form id="subscriptionForm" action="{{ route('operateur.souscriptions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

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

            <input type="hidden" name="clientCategory" id="clientCategory" required>

            <div class="buttons">
                <button type="button" class="btn-primary" onclick="nextStep()" id="continueCategory" disabled>Continuer</button>
            </div>
        </div>

        <!-- ÉTAPE 1 -->
        <div class="step" id="step1">
            <h3>Remplissez le formulaire de souscription</h3>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="bi bi-person"></i> Nom et Prénom</label>
                    <input type="text" name="fullName" required placeholder="Nom complet">
                </div>

                <div class="form-group">
                    <label><i class="bi bi-calendar"></i> Date de naissance</label>
                    <input type="date" name="birthDate" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="bi bi-geo-alt"></i> Lieu de naissance</label>
                    <input type="text" name="birthPlace" required placeholder="Ville">
                </div>

                <div class="form-group">
                    <label><i class="bi bi-globe"></i> Nationalité</label>
                    <input type="text" name="nationality" required placeholder="Pays">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="bi bi-person-plus"></i> Nombre d'enfants</label>
                    <input type="number" name="children" min="0" required placeholder="0">
                </div>

                <div class="form-group">
                    <label><i class="bi bi-person-heart"></i> Ayant droit</label>
                    <input type="text" name="heirs" required placeholder="Nom de l'ayant droit">
                </div>
            </div>

            <div class="form-group">
                <label><i class="bi bi-envelope"></i> Email</label>
                <input type="email" name="email" required placeholder="exemple@email.com">
            </div>

            <div class="form-group">
                <label><i class="bi bi-cash"></i> Salaire mensuel</label>
                <input type="text" name="salary" required placeholder="Montant en FCFA">
            </div>

            <div class="form-group">
                <label>Situation matrimoniale:</label>
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="checkbox" name="maritalStatus" value="Célibataire" id="cel">
                        <label for="cel">Célibataire</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="maritalStatus" value="Divorcé(e)" id="div">
                        <label for="div">Divorcé(e)</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="maritalStatus" value="Marié(e)" id="mar">
                        <label for="mar">Marié(e)</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="maritalStatus" value="Veuf(ve)" id="veuf">
                        <label for="veuf">Veuf(ve)</label>
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
            <h3>Remplissez le formulaire de souscription</h3>

            <div class="form-group">
                <label>Nature de la pièce:</label>
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="checkbox" name="idType" value="CNI" id="cni">
                        <label for="cni">CNI</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="idType" value="Passeport" id="pass">
                        <label for="pass">Passeport</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="idType" value="Carte consulaire" id="cons">
                        <label for="cons">Carte consulaire</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label><i class="bi bi-file-text"></i> Numéro CNI / Passeport</label>
                <input type="text" name="idNumber" required placeholder="Numéro d'identification">
            </div>

            <div class="form-group">
                <label>Téléverser la CNI/Passeport</label>
                <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
                    <div class="file-upload-icon"><i class="bi bi-cloud-upload"></i></div>
                    <div class="file-upload-text">
                        Joindre un document en PDF, JPG, PNG<br>
                        (Taille maximale 10 Mo)
                    </div>
                </div>
                <input type="file" id="fileInput" name="idFile" accept=".pdf,.jpg,.jpeg,.png" style="display:none" onchange="updateFileName(this)">
                <div id="fileName" style="margin-top: 10px; font-size: 13px; color: #27ae60;"></div>
            </div>

            <div class="form-group">
                <label>Programme</label>
                <select name="program" required>
                    <option value="">-- Sélectionnez un programme --</option>
                    @foreach($projets as $projet)
                        <option value="{{ $projet->id }}" 
                                data-prix-terrain="{{ $projet->prix_terrains }}"
                                data-prix-duplex="{{ $projet->prix_duplex }}"
                                data-prix-villa="{{ $projet->prix_villa }}"
                                data-prix-appartement="{{ $projet->prix_appartement }}"
                                data-pourcentage-apport="{{ $projet->pourcentage_apport }}"
                                data-frais-souscription="{{ $projet->frais_souscription }}">
                            {{ $projet->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>La durée du contrat</label>
                <div class="form-row">
                    <div>
                        <label style="font-size: 12px; color: #888;"><i class="bi bi-calendar"></i> Date de début</label>
                        <input type="date" name="startDate" required>
                    </div>
                    <div>
                        <label style="font-size: 12px; color: #888;"><i class="bi bi-calendar"></i> Date de fin</label>
                        <input type="date" name="endDate" required>
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
            <h3>Remplissez le formulaire de souscription</h3>

            <div class="form-group">
                <label>Types de logement :</label>
                <div class="housing-options">
                    <div class="option-card" onclick="selectOption(this, 'housingType', 'Villa basse 3 pièces')">
                        <div class="option-checkbox"></div>
                        <span>Villa basse 3 pièces</span>
                    </div>
                    <div class="option-card" onclick="selectOption(this, 'housingType', 'Villa duplex 4 pièces')">
                        <div class="option-checkbox"></div>
                        <span>Villa duplex 4 pièces</span>
                    </div>
                    <div class="option-card" onclick="selectOption(this, 'housingType', 'Appartement 3 pièces')">
                        <div class="option-checkbox"></div>
                        <span>Appartement 3 pièces</span>
                    </div>
                    <div class="option-card" onclick="selectOption(this, 'housingType', 'Appartement 4 pièces')">
                        <div class="option-checkbox"></div>
                        <span>Appartement 4 pièces</span>
                    </div>
                </div>
                <input type="hidden" name="housingType" id="housingType" required>
            </div>

            <div class="form-group">
                <label>Mode paiement</label>
                <div class="payment-options">
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'Crédit bancaire')">
                        <div class="option-checkbox"></div>
                        <span>Crédit bancaire</span>
                    </div>
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'Tempérament')">
                        <div class="option-checkbox"></div>
                        <span>Tempérament</span>
                    </div>
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'Cash')">
                        <div class="option-checkbox"></div>
                        <span>Cash</span>
                    </div>
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'Prélèvement à la source')">
                        <div class="option-checkbox"></div>
                        <span>Prélèvement à la source</span>
                    </div>
                    <div class="option-card" onclick="selectOption(this, 'paymentMode', 'Virement')">
                        <div class="option-checkbox"></div>
                        <span>Virement</span>
                    </div>
                </div>
                <input type="hidden" name="paymentMode" id="paymentMode" required>
            </div>

            <div class="summary-box">
                <h4>💰 Valeur de la souscription : <span id="valeurSouscription">30 000 000</span> FCFA</h4>
                <div class="summary-line">
                    <span>Apport initial (<span id="pourcentageApport">10</span>%)</span>
                    <strong><span id="apportInitial">3 000 000</span> FCFA</strong>
                </div>
            </div>

            <div class="warning-box">
                ⚠️ Les frais de souscription s'élèvent à <span id="fraisSouscription">500 000</span> FCFA (non remboursables)
            </div>
            
            <input type="hidden" name="valeur_souscription" id="valeur_souscription_input" value="30000000">
            <input type="hidden" name="apport_initial" id="apport_initial_input" value="3000000">
            <input type="hidden" name="frais_souscription" id="frais_souscription_input" value="500000">

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
                <div class="checkbox-item">
                    <input type="checkbox" id="certify" required>
                    <label for="certify">Je certifie que les informations fournies sont exactes et l'autorise leur traitement</label>
                </div>
            </div>

            <div class="buttons">
                <button type="button" class="btn-secondary" onclick="prevStep()">Retour</button>
                <button type="submit" class="btn-primary">Enregistrer la souscription</button>
            </div>
        </div>

    </form>

    <!-- Écran de succès -->
    <div class="success-screen" id="successScreen">
        <div class="success-icon"><i class="bi bi-check-circle"></i></div>
        <h3>Votre souscription a été enregistrée avec succès</h3>
        <p>La souscription a été soumise pour validation.</p>
        <button type="button" class="btn-primary" onclick="window.location.href='{{ route('operateur.dashboard') }}'">Retour au tableau de bord</button>
    </div>

</div>

<script>
    let currentStep = 0;
    const totalSteps = 5;

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
        
        for (let field of requiredFields) {
            if (!field.value.trim()) {
                alert('Veuillez remplir tous les champs obligatoires');
                field.focus();
                return false;
            }
        }
        return true;
    }

    function generateRecap() {
        const formData = new FormData(document.getElementById('subscriptionForm'));
        let recapHTML = '';
        
        recapHTML += '<div class="recap-item"><strong>Catégorie:</strong> ' + formData.get('clientCategory') + '</div>';
        recapHTML += '<div class="recap-item"><strong>Nom:</strong> ' + formData.get('fullName') + '</div>';
        recapHTML += '<div class="recap-item"><strong>Date de naissance:</strong> ' + formData.get('birthDate') + '</div>';
        recapHTML += '<div class="recap-item"><strong>Nationalité:</strong> ' + formData.get('nationality') + '</div>';
        recapHTML += '<div class="recap-item"><strong>Email:</strong> ' + formData.get('email') + '</div>';
        recapHTML += '<div class="recap-item"><strong>Type de logement:</strong> ' + formData.get('housingType') + '</div>';
        recapHTML += '<div class="recap-item"><strong>Mode de paiement:</strong> ' + formData.get('paymentMode') + '</div>';
        recapHTML += '<div class="recap-item"><strong>Valeur de souscription:</strong> ' + formatMontant(parseInt(formData.get('valeur_souscription'))) + ' FCFA</div>';
        recapHTML += '<div class="recap-item"><strong>Apport initial:</strong> ' + formatMontant(parseInt(formData.get('apport_initial'))) + ' FCFA</div>';
        recapHTML += '<div class="recap-item"><strong>Frais de souscription:</strong> ' + formatMontant(parseInt(formData.get('frais_souscription'))) + ' FCFA [non remboursables]</div>';
        
        document.getElementById('recapContent').innerHTML = recapHTML;
    }

    document.getElementById('subscriptionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!document.getElementById('certify').checked) {
            alert('Veuillez certifier que les informations sont exactes');
            return;
        }
        
        // Soumettre le formulaire
        this.submit();
    });

    // Initialize
    showStep(0);
    
    // Fonction pour formater les montants en FCFA
    function formatMontant(montant) {
        return new Intl.NumberFormat('fr-FR').format(montant);
    }
    
    // Fonction pour calculer les valeurs dynamiquement
    function calculerValeursSouscription() {
        const programmeSelect = document.querySelector('select[name="program"]');
        const typeLogement = document.getElementById('housingType').value;
        
        if (!programmeSelect.value || !typeLogement) {
            return; // Pas assez d'informations pour calculer
        }
        
        const programme = programmeSelect.options[programmeSelect.selectedIndex];
        const prixTerrain = parseFloat(programme.getAttribute('data-prix-terrain')) || 0;
        const prixDuplex = parseFloat(programme.getAttribute('data-prix-duplex')) || 0;
        const prixVilla = parseFloat(programme.getAttribute('data-prix-villa')) || 0;
        const prixAppartement = parseFloat(programme.getAttribute('data-prix-appartement')) || 0;
        const pourcentageApport = parseFloat(programme.getAttribute('data-pourcentage-apport')) || 10;
        const fraisSouscription = parseFloat(programme.getAttribute('data-frais-souscription')) || 500000;
        
        let valeurSouscription = 0;
        
        // Déterminer la valeur selon le type de logement
        switch(typeLogement) {
            case 'Villa basse 3 pièces':
                valeurSouscription = prixVilla;
                break;
            case 'Villa duplex 4 pièces':
                valeurSouscription = prixDuplex;
                break;
            case 'Appartement 3 pièces':
            case 'Appartement 4 pièces':
                valeurSouscription = prixAppartement;
                break;
            default:
                valeurSouscription = prixTerrain; // Par défaut
        }
        
        if (valeurSouscription > 0) {
            const apportInitial = Math.round(valeurSouscription * (pourcentageApport / 100));
            
            // Mettre à jour l'affichage
            document.getElementById('valeurSouscription').textContent = formatMontant(valeurSouscription);
            document.getElementById('pourcentageApport').textContent = pourcentageApport;
            document.getElementById('apportInitial').textContent = formatMontant(apportInitial);
            document.getElementById('fraisSouscription').textContent = formatMontant(fraisSouscription);
            
            // Mettre à jour les champs cachés
            document.getElementById('valeur_souscription_input').value = valeurSouscription;
            document.getElementById('apport_initial_input').value = apportInitial;
            document.getElementById('frais_souscription_input').value = fraisSouscription;
        }
    }
    
    // Écouter les changements de programme et de type de logement
    document.addEventListener('DOMContentLoaded', function() {
        const programmeSelect = document.querySelector('select[name="program"]');
        const typeLogementInputs = document.querySelectorAll('.housing-options .option-card');
        
        if (programmeSelect) {
            programmeSelect.addEventListener('change', calculerValeursSouscription);
        }
        
        // Écouter les changements de type de logement
        typeLogementInputs.forEach(card => {
            card.addEventListener('click', function() {
                setTimeout(calculerValeursSouscription, 100); // Petit délai pour laisser le DOM se mettre à jour
            });
        });
    });
</script>

@endsection
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulaire de souscription – JARE INDUSTRIES</title>
  <style>
    /* Styles identiques à ton original */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f5f5;
      min-height: 100vh;
      padding: 20px;
    }

    .container {
      max-width: 600px;
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
<base target="_blank">
</head>
<body>

<div class="container">
  <div class="header">
    <div class="logo">
      <div class="logo-subtitle">INDUSTRIES</div>
    </div>
    <h2>Nouvelle souscription</h2>
    <p>Promoteur immobilier agréé</p>
  </div>

  <div class="progress-bar">
    <div class="progress-fill" id="progressFill" style="width: 20%"></div>
  </div>

  <div class="step-indicator" id="stepIndicator">1/5</div>

  <form id="subscriptionForm">

    <!-- ÉTAPE 0: Sélection catégorie -->
    <div class="step active" id="step0">
      <h3>Choisissez la catégorie de client</h3>

      <div class="category-selection">
        <div class="category-card" onclick="selectCategory(this, 'Client individuel')">
          <div class="category-icon">👤</div>
          <div class="category-label">Client<br>individuel</div>
        </div>

        <div class="category-card" onclick="selectCategory(this, 'Association Syndicat Mutuelle')">
          <div class="category-icon">👥</div>
          <div class="category-label">Association<br>Syndicat<br>Mutuelle</div>
        </div>

        <div class="category-card" onclick="selectCategory(this, 'Client diaspora')">
          <div class="category-icon">🌍</div>
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
          <label>👤 Nom et Prénom</label>
          <input type="text" name="fullName" required placeholder="Nom complet">
        </div>

        <div class="form-group">
          <label>📅 Date de naissance</label>
          <input type="date" name="birthDate" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>📍 Lieu de naissance</label>
          <input type="text" name="birthPlace" required placeholder="Ville">
        </div>

        <div class="form-group">
          <label>🌍 Nationalité</label>
          <input type="text" name="nationality" required placeholder="Pays">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>👶 Nombre d'enfants</label>
          <input type="number" name="children" min="0" required placeholder="0">
        </div>

        <div class="form-group">
          <label>👨‍👩‍👧 Ayant droit</label>
          <input type="text" name="heirs" required placeholder="Nom de l'ayant droit">
        </div>
      </div>

      <div class="form-group">
        <label>📧 Email</label>
        <input type="email" name="email" required placeholder="exemple@email.com">
      </div>

      <div class="form-group">
        <label>💰 Salaire mensuel</label>
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
        <label>📄 Numéro CNI / Passeport</label>
        <input type="text" name="idNumber" required placeholder="Numéro d'identification">
      </div>

      <div class="form-group">
        <label>Téléverser la CNI/Passeport</label>
        <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
          <div class="file-upload-icon">☁️</div>
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
          <option>Programme A</option>
          <option>Programme B</option>
          <option>Programme C</option>
        </select>
      </div>

      <div class="form-group">
        <label>La durée du contrat</label>
        <div class="form-row">
          <div>
            <label style="font-size: 12px; color: #888;">📅 Date de début</label>
            <input type="date" name="startDate" required>
          </div>
          <div>
            <label style="font-size: 12px; color: #888;">📅 Date de fin</label>
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
        <h4>💰 Valeur de la souscription : 30 000 000 FCFA</h4>
        <div class="summary-line">
          <span>Apport initial (10%)</span>
          <strong>3 000 000 FCFA</strong>
        </div>
      </div>

      <div class="warning-box">
        ⚠️ Les frais de souscription s'élèvent à 500 000 FCFA (non remboursables)
      </div>

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
        <button type="submit" class="btn-primary">Continuer</button>
      </div>
    </div>

  </form>

  <!-- Écran de succès -->
  <div class="success-screen" id="successScreen">
    <div class="success-icon">✓</div>
    <h3>Votre souscription a été soumise à la comptabilité</h3>
    <p>Vous recevrez une confirmation par email sous peu.</p>
    <button type="button" class="btn-primary" onclick="resetForm()">Retour à la page d'accueil</button>
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
    const fileName = input.files[0]?.name;
    if (fileName) {
      document.getElementById('fileName').textContent = '✓ Fichier sélectionné : ' + fileName;
    }
  }

  function validateStep() {
    const currentStepElement = document.getElementById('step' + currentStep);
    const inputs = currentStepElement.querySelectorAll('input[required], select[required]');
    let valid = true;

    inputs.forEach(input => {
      if (input.type === 'checkbox' && input.hasAttribute('required')) {
        const checkboxes = currentStepElement.querySelectorAll(`input[name="${input.name}"]`);
        const checked = Array.from(checkboxes).some(cb => cb.checked);
        if (!checked) valid = false;
      } else if (input.type === 'hidden') {
        if (!input.value) valid = false;
      } else {
        if (!input.value) valid = false;
      }
    });

    return valid;
  }

  function generateRecap() {
    const form = document.getElementById('subscriptionForm');
    const formData = new FormData(form);

    let recap = '<h4>Informations personnelles</h4>';
    recap += `<div class="recap-item"><strong>Nom :</strong> ${formData.get('fullName') || '-'}</div>`;
    recap += `<div class="recap-item"><strong>Email :</strong> ${formData.get('email') || '-'}</div>`;
    recap += `<div class="recap-item"><strong>Catégorie :</strong> ${formData.get('clientCategory') || '-'}</div>`;

    recap += '<h4 style="margin-top: 20px;">Logement & Programme</h4>';
    recap += `<div class="recap-item"><strong>Type de logement :</strong> ${formData.get('housingType') || '-'}</div>`;
    recap += `<div class="recap-item"><strong>Programme :</strong> ${formData.get('program') || '-'}</div>`;
    recap += `<div class="recap-item"><strong>Durée du projet :</strong> ${formData.get('startDate')} - ${formData.get('endDate')}</div>`;

    recap += '<h4 style="margin-top: 20px;">Paiement</h4>';
    recap += `<div class="recap-item"><strong>Mode de paiement :</strong> ${formData.get('paymentMode') || '-'}</div>`;
    recap += `<div class="recap-item"><strong>Superficie totale :</strong> 3 000 000 FCFA</div>`;
    recap += `<div class="recap-item"><strong>Apport initial :</strong> 3 000 000 FCFA (10%)</div>`;
    recap += `<div class="recap-item"><strong>Frais de souscription :</strong> 500 000 FCFA [non remboursables]</div>`;

    document.getElementById('recapContent').innerHTML = recap;
  }

  function nextStep() {
    if (!validateStep()) {
      alert('Veuillez remplir tous les champs obligatoires.');
      return;
    }

    if (currentStep === 3) {
      generateRecap();
    }

    if (currentStep < totalSteps - 1) {
      currentStep++;
      showStep(currentStep);
    }
  }

  function prevStep() {
    if (currentStep > 0) {
      currentStep--;
      showStep(currentStep);
    }
  }

  function resetForm() {
    // Réinitialiser le formulaire
    document.getElementById('subscriptionForm').reset();

    // Masquer l'écran de succès
    document.getElementById('successScreen').classList.remove('active');

    // Réafficher le container principal
    document.querySelector('.container').style.display = 'block';

    // Retourner à l'étape 0
    currentStep = 0;
    showStep(currentStep);

    // Retirer la sélection des cartes de catégorie
    document.querySelectorAll('.category-card').forEach(c => c.classList.remove('selected'));

    // Retirer la sélection des cartes d'options (logement et paiement)
    document.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));

    // Désactiver le bouton continuer de la catégorie
    document.getElementById('continueCategory').disabled = true;

    // Effacer le nom du fichier téléversé
    document.getElementById('fileName').textContent = '';

    // Réinitialiser les champs cachés
    document.getElementById('clientCategory').value = '';
    document.getElementById('housingType').value = '';
    document.getElementById('paymentMode').value = '';

    // Décocher toutes les checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);

    // Réinitialiser la barre de progression
    updateProgress();
  }

  document.getElementById('subscriptionForm').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!document.getElementById('certify').checked) {
      alert('Veuillez certifier les informations avant de continuer.');
      return;
    }

    // Masquer le formulaire et afficher l'écran de succès
    document.querySelector('.container').style.display = 'none';
    document.getElementById('successScreen').classList.add('active');
  });

  // Gérer les checkboxes mutuellement exclusives pour l'état matrimonial
  document.querySelectorAll('input[name="maritalStatus"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      if (this.checked) {
        document.querySelectorAll('input[name="maritalStatus"]').forEach(cb => {
          if (cb !== this) cb.checked = false;
        });
      }
    });
  });

  // Gérer les checkboxes mutuellement exclusives pour le type de pièce
  document.querySelectorAll('input[name="idType"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      if (this.checked) {
        document.querySelectorAll('input[name="idType"]').forEach(cb => {
          if (cb !== this) cb.checked = false;
        });
      }
    });
  });

  // Initialiser
  showStep(0);
</script>

</body>
</html>
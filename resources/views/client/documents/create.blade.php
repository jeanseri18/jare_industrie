@extends('layouts.client')

@section('content')
<style>
    .upload-container {
        padding: 2rem 1.5rem;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .upload-wrapper {
        max-width: 700px;
        margin: 0 auto;
    }

    .page-header {
        text-align: center;
        margin-bottom: 2rem;
        padding: 1.5rem 2rem;
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .page-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 2rem;
        animation: scaleIn 0.6s ease-out 0.2s backwards;
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.5); }
        to { opacity: 1; transform: scale(1); }
    }

    .page-header h1 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: #64748b;
        font-size: 0.95rem;
    }

    .upload-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out 0.3s backwards;
    }

    .form-group {
        margin-bottom: 1.8rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.6rem;
        color: #1e293b;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .form-label .required {
        color: #ef4444;
        margin-left: 0.2rem;
    }

    .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b5998;
        background: white;
        box-shadow: 0 0 0 4px rgba(59, 89, 152, 0.1);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 0.4rem;
        display: block;
    }

    /* File Upload Area */
    .file-upload-area {
        position: relative;
        border: 3px dashed #cbd5e1;
        border-radius: 12px;
        padding: 2.5rem 1.5rem;
        text-align: center;
        background: #f8fafc;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .file-upload-area:hover {
        border-color: #3b5998;
        background: white;
    }

    .file-upload-area.dragover {
        border-color: #3b5998;
        background: rgba(59, 89, 152, 0.05);
    }

    .file-input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    .upload-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 2rem;
        transition: transform 0.3s ease;
    }

    .file-upload-area:hover .upload-icon {
        transform: scale(1.1);
    }

    .upload-text h4 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .upload-text p {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .browse-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.2rem;
        background: rgba(59, 89, 152, 0.1);
        color: #3b5998;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .file-upload-area:hover .browse-button {
        background: #3b5998;
        color: white;
    }

    .file-info {
        margin-top: 1rem;
        padding: 0.8rem;
        background: rgba(16, 185, 129, 0.1);
        border-radius: 8px;
        display: none;
    }

    .file-info.show {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .file-info i {
        font-size: 1.5rem;
        color: #10b981;
    }

    .file-details {
        flex: 1;
    }

    .file-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
    }

    .file-size {
        font-size: 0.8rem;
        color: #64748b;
    }

    .file-remove {
        background: none;
        border: none;
        color: #ef4444;
        cursor: pointer;
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }

    .file-remove:hover {
        transform: scale(1.2);
    }

    .form-help {
        margin-top: 0.5rem;
        font-size: 0.85rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-help i {
        color: #3b5998;
    }

    /* Action Buttons */
    .form-actions {
        display: flex;
        gap: 1rem;
        padding: 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        margin-top: 2rem;
    }

    .btn {
        flex: 1;
        padding: 0.9rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        text-decoration: none;
    }

    .btn-cancel {
        background: white;
        color: #64748b;
        border: 2px solid #e2e8f0;
    }

    .btn-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }

    .btn-submit {
        background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 89, 152, 0.3);
        position: relative;
        overflow: hidden;
    }

    .btn-submit::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
    }

    .btn-submit:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 89, 152, 0.4);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    /* Tips Section */
    .tips-section {
        margin-top: 2rem;
        padding: 1.5rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        animation: fadeInUp 0.6s ease-out 0.5s backwards;
    }

    .tips-title {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1rem;
    }

    .tips-title i {
        color: #3b5998;
    }

    .tips-list {
        display: grid;
        gap: 0.8rem;
    }

    .tip-item {
        display: flex;
        align-items: start;
        gap: 0.8rem;
        font-size: 0.9rem;
        color: #64748b;
    }

    .tip-item i {
        color: #10b981;
        margin-top: 0.2rem;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .upload-container {
            padding: 1rem;
        }

        .page-header h1 {
            font-size: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .file-upload-area {
            padding: 2rem 1rem;
        }
    }
</style>

<div class="upload-container">
    <div class="upload-wrapper">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <h1>Télécharger un document</h1>
            <p>Ajoutez un nouveau document à votre dossier</p>
        </div>

        <!-- Upload Card -->
        <div class="upload-card">
            <form action="{{ route('client.documents.store') }}" method="POST" enctype="multipart/form-data" style="padding: 2rem;">
                @csrf
                
                <!-- Titre -->
                <div class="form-group">
                    <label for="titre" class="form-label">
                        Titre du document<span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="form-control @error('titre') is-invalid @enderror" 
                        id="titre" 
                        name="titre" 
                        value="{{ old('titre') }}" 
                        placeholder="Ex: Carte d'identité"
                        required
                    >
                    @error('titre')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description" class="form-label">
                        Description (optionnelle)
                    </label>
                    <textarea 
                        class="form-control @error('description') is-invalid @enderror" 
                        id="description" 
                        name="description" 
                        placeholder="Ajoutez des détails supplémentaires sur ce document..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- File Upload -->
                <div class="form-group">
                    <label for="fichier" class="form-label">
                        Fichier<span class="required">*</span>
                    </label>
                    <div class="file-upload-area" id="fileUploadArea">
                        <input 
                            type="file" 
                            class="file-input @error('fichier') is-invalid @enderror" 
                            id="fichier" 
                            name="fichier" 
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" 
                            required
                        >
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="upload-text">
                            <h4>Glissez-déposez votre fichier ici</h4>
                            <p>ou</p>
                            <span class="browse-button">
                                <i class="fas fa-folder-open"></i>
                                Parcourir les fichiers
                            </span>
                        </div>
                        <div class="file-info" id="fileInfo">
                            <i class="fas fa-file-alt"></i>
                            <div class="file-details">
                                <div class="file-name" id="fileName"></div>
                                <div class="file-size" id="fileSize"></div>
                            </div>
                            <button type="button" class="file-remove" id="fileRemove">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-help">
                        <i class="fas fa-info-circle"></i>
                        <span>Formats: PDF, DOC, DOCX, JPG, JPEG, PNG • Taille max: 10MB</span>
                    </div>
                    @error('fichier')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <a href="{{ route('client.documents') }}" class="btn btn-cancel">
                        <i class="fas fa-times"></i>
                        <span>Annuler</span>
                    </a>
                    <button type="submit" class="btn btn-submit" id="submitBtn">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span style="position: relative; z-index: 1;">Télécharger</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tips Section -->
        <div class="tips-section">
            <div class="tips-title">
                <i class="fas fa-lightbulb"></i>
                <span>Conseils pour un téléchargement réussi</span>
            </div>
            <div class="tips-list">
                <div class="tip-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Assurez-vous que le document est lisible et de bonne qualité</span>
                </div>
                <div class="tip-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Vérifiez que toutes les informations sont visibles</span>
                </div>
                <div class="tip-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Utilisez le format PDF pour les documents officiels</span>
                </div>
                <div class="tip-item">
                    <i class="fas fa-check-circle"></i>
                    <span>La validation peut prendre jusqu'à 48 heures</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // File Upload Handling
    const fileInput = document.getElementById('fichier');
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const fileRemove = document.getElementById('fileRemove');
    const submitBtn = document.getElementById('submitBtn');

    // File selection
    fileInput.addEventListener('change', function(e) {
        handleFile(e.target.files[0]);
    });

    // Drag and drop
    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        fileUploadArea.classList.add('dragover');
    });

    fileUploadArea.addEventListener('dragleave', function() {
        fileUploadArea.classList.remove('dragover');
    });

    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        fileUploadArea.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        fileInput.files = e.dataTransfer.files;
        handleFile(file);
    });

    // Remove file
    fileRemove.addEventListener('click', function() {
        fileInput.value = '';
        fileInfo.classList.remove('show');
        submitBtn.disabled = false;
    });

    function handleFile(file) {
        if (file) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            fileInfo.classList.add('show');
            
            // Check file size (10MB max)
            if (file.size > 10 * 1024 * 1024) {
                alert('Le fichier est trop volumineux. Taille maximale: 10MB');
                fileInput.value = '';
                fileInfo.classList.remove('show');
                return;
            }
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    // Form validation
    document.querySelector('form').addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span style="position: relative; z-index: 1;">Téléchargement...</span>';
    });
</script>
@endsection
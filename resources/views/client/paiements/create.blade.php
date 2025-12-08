@extends('layouts.client')

@section('title', 'Ajouter un paiement')

@section('content')
<style>
    .paiement-container {
        padding: 2rem 1.5rem;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .paiement-wrapper {
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

    .paiement-card {
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

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }

    .btn-submit {
        padding: 0.8rem 1.8rem;
        background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(59, 89, 152, 0.3);
        cursor: pointer;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 89, 152, 0.4);
    }

    .btn-cancel {
        padding: 0.8rem 1.8rem;
        background: rgba(100, 116, 139, 0.1);
        color: #64748b;
        border: 2px solid rgba(100, 116, 139, 0.2);
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #64748b;
        color: white;
        border-color: #64748b;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="paiement-container">
    <div class="paiement-wrapper">
        <div class="page-header">
            <div class="page-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <h1>Ajouter un paiement</h1>
            <p>Enregistrez votre paiement pour validation</p>
        </div>

        <div class="paiement-card">
            <form action="{{ route('client.paiements.store') }}" method="POST">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="montant" class="form-label">Montant <span class="required">*</span></label>
                        <input type="number" step="0.01" min="0.01" class="form-control @error('montant') is-invalid @enderror" id="montant" name="montant" value="{{ old('montant') }}" required>
                        @error('montant')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="date_paiement" class="form-label">Date de paiement <span class="required">*</span></label>
                        <input type="date" class="form-control @error('date_paiement') is-invalid @enderror" id="date_paiement" name="date_paiement" value="{{ old('date_paiement', date('Y-m-d')) }}" required>
                        @error('date_paiement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="methode_paiement" class="form-label">Méthode de paiement <span class="required">*</span></label>
                        <select class="form-control @error('methode_paiement') is-invalid @enderror" id="methode_paiement" name="methode_paiement" required>
                            <option value="">Sélectionner une méthode</option>
                            <option value="virement" {{ old('methode_paiement') == 'virement' ? 'selected' : '' }}>Virement bancaire</option>
                            <option value="carte" {{ old('methode_paiement') == 'carte' ? 'selected' : '' }}>Carte bancaire</option>
                            <option value="cash" {{ old('methode_paiement') == 'cash' ? 'selected' : '' }}>Espèces</option>
                        </select>
                        @error('methode_paiement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="reference_paiement" class="form-label">Référence de paiement <span class="required">*</span></label>
                        <input type="text" class="form-control @error('reference_paiement') is-invalid @enderror" id="reference_paiement" name="reference_paiement" value="{{ old('reference_paiement') }}" required>
                        @error('reference_paiement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="observations" class="form-label">Observations</label>
                    <textarea class="form-control @error('observations') is-invalid @enderror" id="observations" name="observations" rows="3">{{ old('observations') }}</textarea>
                    @error('observations')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('client.paiements') }}" class="btn-cancel">Annuler</a>
                    <button type="submit" class="btn-submit">Enregistrer le paiement</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
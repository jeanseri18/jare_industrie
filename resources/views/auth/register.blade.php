@extends('layouts.guest')

@section('content')
<style>
    .register-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
         background-image: url('{{ asset("stylish-white-interior-2024-10-18-09-44-08-utc 1.png") }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding: 40px 20px;
        position: relative;
        overflow: hidden;
    }

    .register-container::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 8s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.5; }
        50% { transform: scale(1.1) rotate(180deg); opacity: 0.8; }
    }

    .register-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
        max-width: 1100px;
        width: 100%;
        display: flex;
        animation: slideUp 0.6s ease-out;
        position: relative;
        z-index: 1;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .register-left {
        flex: 0.8;
        background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        padding: 60px 40px;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .register-left::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .register-left-content {
        position: relative;
        z-index: 1;
    }

    .register-logo {
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 30px;
        animation: fadeIn 0.8s ease-out 0.3s backwards;
    }

    .register-left h2 {
        font-size: 1.8rem;
        margin-bottom: 15px;
        font-weight: 700;
        animation: fadeIn 0.8s ease-out 0.4s backwards;
    }

    .register-left p {
        font-size: 0.95rem;
        opacity: 0.9;
        line-height: 1.6;
        animation: fadeIn 0.8s ease-out 0.5s backwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .register-steps {
        margin-top: 40px;
        animation: fadeIn 0.8s ease-out 0.6s backwards;
    }

    .step-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        transition: transform 0.3s ease;
    }

    .step-item:hover {
        transform: translateX(5px);
    }

    .step-number {
        width: 35px;
        height: 35px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .step-item:hover .step-number {
        background: rgba(255,255,255,0.3);
        transform: scale(1.1);
    }

    .register-right {
        flex: 1.2;
        padding: 60px 50px;
        max-height: 90vh;
        overflow-y: auto;
    }

    .register-right::-webkit-scrollbar {
        width: 8px;
    }

    .register-right::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .register-right::-webkit-scrollbar-thumb {
        background: #3b5998;
        border-radius: 10px;
    }

    .register-right::-webkit-scrollbar-thumb:hover {
        background: #2d4373;
    }

    .register-header {
        text-align: center;
        margin-bottom: 35px;
        animation: fadeIn 0.8s ease-out 0.3s backwards;
    }

    .register-header h3 {
        font-size: 1.8rem;
        color: #333;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .register-header p {
        color: #666;
        font-size: 0.95rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
        animation: fadeIn 0.8s ease-out backwards;
    }

    .form-group:nth-child(1) { animation-delay: 0.4s; }
    .form-group:nth-child(2) { animation-delay: 0.45s; }
    .form-group:nth-child(3) { animation-delay: 0.5s; }
    .form-group:nth-child(4) { animation-delay: 0.55s; }
    .form-group:nth-child(5) { animation-delay: 0.6s; }
    .form-group:nth-child(6) { animation-delay: 0.65s; }
    .form-group:nth-child(7) { animation-delay: 0.7s; }
    .form-group:nth-child(8) { animation-delay: 0.75s; }

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: #333;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        transition: color 0.3s ease;
        z-index: 1;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #3b5998;
        background: white;
        box-shadow: 0 0 0 4px rgba(59, 89, 152, 0.1);
    }

    .form-control:focus ~ .input-icon,
    .form-select:focus ~ .input-icon {
        color: #3b5998;
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
        padding-top: 12px;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
    }

    .btn-register {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(59, 89, 152, 0.4);
        animation: fadeIn 0.8s ease-out 0.8s backwards;
        position: relative;
        overflow: hidden;
        margin-top: 10px;
    }

    .btn-register::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
    }

    .btn-register:hover::before {
        width: 400px;
        height: 400px;
    }

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 89, 152, 0.5);
    }

    .btn-register:active {
        transform: translateY(0);
    }

    .divider {
        text-align: center;
        margin: 25px 0;
        position: relative;
        animation: fadeIn 0.8s ease-out 0.9s backwards;
    }

    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #e0e0e0;
    }

    .divider span {
        background: white;
        padding: 0 15px;
        color: #999;
        font-size: 0.85rem;
        position: relative;
        z-index: 1;
    }

    .login-link {
        text-align: center;
        color: #666;
        font-size: 0.9rem;
        animation: fadeIn 0.8s ease-out 1s backwards;
    }

    .login-link a {
        color: #3b5998;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
    }

    .login-link a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: #3b5998;
        transition: width 0.3s ease;
    }

    .login-link a:hover {
        color: #2d4373;
    }

    .login-link a:hover::after {
        width: 100%;
    }

    .password-strength {
        margin-top: 8px;
        font-size: 0.85rem;
    }

    .strength-bar {
        height: 4px;
        background: #e0e0e0;
        border-radius: 2px;
        overflow: hidden;
        margin-top: 5px;
    }

    .strength-fill {
        height: 100%;
        width: 0;
        transition: all 0.3s ease;
        border-radius: 2px;
    }

    @media (max-width: 968px) {
        .register-card {
            flex-direction: column;
        }

        .register-left {
            padding: 40px 30px;
        }

        .register-right {
            padding: 40px 30px;
            max-height: none;
        }

        .register-left h2 {
            font-size: 1.5rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .register-container {
            padding: 20px 10px;
        }

        .register-right {
            padding: 30px 20px;
        }
    }
</style>

<div class="register-container">
    <div class="register-card">
        <div class="register-left">
            <div class="register-left-content">
                <div class="register-logo">
                    <i class="fas fa-user-plus" style="font-size: 2rem; color: #3b5998;"></i>
                </div>
                <h2>Créez votre compte</h2>
                <p>Rejoignez Jare industrie et profitez d'une gestion immobilière simplifiée et efficace.</p>
                
                <div class="register-steps">
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <span>Remplissez le formulaire</span>
                    </div>
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <span>Vérifiez votre email</span>
                    </div>
                    <div class="step-item">
                        <div class="step-number">3</div>
                        <span>Accédez à votre espace</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="register-right">
            <div class="register-header">
                    <h3>Inscription Utilisateur</h3>
                    <p>Veuillez remplir tous les champs ci-dessous</p>
                </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="role" class="form-label">Rôle</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user-tag input-icon"></i>
                        <select id="role" class="form-select @error('role') is-invalid @enderror" name="role" required>
                            <option value="">Sélectionner le rôle</option>
                            <option value="dg" {{ old('role') == 'dg' ? 'selected' : '' }}>Directeur Général</option>
                            <option value="comptable" {{ old('role') == 'comptable' ? 'selected' : '' }}>Comptable</option>
                            <option value="operateur" {{ old('role') == 'operateur' ? 'selected' : '' }}>Opérateur de saisie</option>
                            <option value="chef_commercial" {{ old('role') == 'chef_commercial' ? 'selected' : '' }}>Chef Commercial</option>
                            <option value="admin_technique" {{ old('role') == 'admin_technique' ? 'selected' : '' }}>Administrateur Technique</option>
                        </select>
                        @error('role')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nom" class="form-label">Nom</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input 
                                id="nom" 
                                type="text" 
                                class="form-control @error('nom') is-invalid @enderror" 
                                name="nom" 
                                value="{{ old('nom') }}" 
                                required 
                                autocomplete="family-name" 
                                autofocus
                                placeholder="Votre nom"
                            >
                            @error('nom')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="prenom" class="form-label">Prénom</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input 
                                id="prenom" 
                                type="text" 
                                class="form-control @error('prenom') is-invalid @enderror" 
                                name="prenom" 
                                value="{{ old('prenom') }}" 
                                required 
                                autocomplete="given-name"
                                placeholder="Votre prénom"
                            >
                            @error('prenom')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <div class="input-wrapper">
                            <i class="fas fa-phone input-icon"></i>
                            <input 
                                id="telephone" 
                                type="tel" 
                                class="form-control @error('telephone') is-invalid @enderror" 
                                name="telephone" 
                                value="{{ old('telephone') }}" 
                                required 
                                autocomplete="tel"
                                placeholder="Votre numéro de téléphone"
                            >
                            @error('telephone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="adresse" class="form-label">Adresse</label>
                        <div class="input-wrapper">
                            <i class="fas fa-map-marker-alt input-icon"></i>
                            <input 
                                id="adresse" 
                                type="text" 
                                class="form-control @error('adresse') is-invalid @enderror" 
                                name="adresse" 
                                value="{{ old('adresse') }}" 
                                required 
                                autocomplete="street-address"
                                placeholder="Votre adresse"
                            >
                            @error('adresse')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                 
                <div class="form-row">
                    <div class="form-group">
                        <label for="email" class="form-label">Adresse Email</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon"></i>
                            <input 
                                id="email" 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="email"
                                placeholder="exemple@email.com"
                            >
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </div>

                   
                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input 
                                id="password" 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                name="password" 
                                required 
                                autocomplete="new-password"
                                placeholder="••••••••"
                            >
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password-confirm" class="form-label">Confirmer le mot de passe</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input 
                                id="password-confirm" 
                                type="password" 
                                class="form-control" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                placeholder="••••••••"
                            >
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    <span style="position: relative; z-index: 1;">S'inscrire</span>
                </button>

                <div class="divider">
                    <span>OU</span>
                </div>

                <div class="login-link">
                    Vous avez déjà un compte? <a href="{{ route('login') }}">Se connecter</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Indicateur de force du mot de passe
    const passwordInput = document.getElementById('password');
    const strengthFill = document.getElementById('strengthFill');
    
    if (passwordInput && strengthFill) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            if (password.length >= 8) strength += 25;
            if (password.match(/[a-z]+/)) strength += 25;
            if (password.match(/[A-Z]+/)) strength += 25;
            if (password.match(/[0-9]+/) || password.match(/[^a-zA-Z0-9]+/)) strength += 25;
            
            strengthFill.style.width = strength + '%';
            
            if (strength <= 25) {
                strengthFill.style.background = '#dc3545';
            } else if (strength <= 50) {
                strengthFill.style.background = '#ffc107';
            } else if (strength <= 75) {
                strengthFill.style.background = '#17a2b8';
            } else {
                strengthFill.style.background = '#28a745';
            }
        });
    }
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
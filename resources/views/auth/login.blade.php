@extends('layouts.guest')

@section('content')
<style>
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-image: url('{{ asset("stylish-white-interior-2024-10-18-09-44-08-utc 1.png") }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding: 20px;
        position: relative;
        overflow: hidden;
    }

    .login-container::before {
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

    .login-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
        max-width: 900px;
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

    .login-left {
        flex: 1;
        background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        padding: 60px 40px;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .login-left::before {
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

    .login-left-content {
        position: relative;
        z-index: 1;
    }

    .login-logo {
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

    .login-left h2 {
        font-size: 2rem;
        margin-bottom: 15px;
        font-weight: 700;
        animation: fadeIn 0.8s ease-out 0.4s backwards;
    }

    .login-left p {
        font-size: 1rem;
        opacity: 0.9;
        line-height: 1.6;
        animation: fadeIn 0.8s ease-out 0.5s backwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .login-features {
        margin-top: 40px;
        animation: fadeIn 0.8s ease-out 0.6s backwards;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .feature-icon {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .feature-item:hover .feature-icon {
        background: rgba(255,255,255,0.3);
        transform: scale(1.1) rotate(5deg);
    }

    .login-right {
        flex: 1;
        padding: 60px 40px;
    }

    .login-header {
        text-align: center;
        margin-bottom: 40px;
        animation: fadeIn 0.8s ease-out 0.3s backwards;
    }

    .login-header h3 {
        font-size: 1.8rem;
        color: #333;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .login-header p {
        color: #666;
        font-size: 0.95rem;
    }

    .form-group {
        margin-bottom: 25px;
        animation: fadeIn 0.8s ease-out backwards;
    }

    .form-group:nth-child(1) { animation-delay: 0.4s; }
    .form-group:nth-child(2) { animation-delay: 0.5s; }
    .form-group:nth-child(3) { animation-delay: 0.6s; }

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
    }

    .form-control {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b5998;
        background: white;
        box-shadow: 0 0 0 4px rgba(59, 89, 152, 0.1);
    }

    .form-control:focus ~ .input-icon {
        color: #3b5998;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
    }

    .remember-forgot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        animation: fadeIn 0.8s ease-out 0.7s backwards;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #3b5998;
    }

    .form-check-label {
        font-size: 0.9rem;
        color: #666;
        cursor: pointer;
        user-select: none;
    }

    .forgot-link {
        color: #3b5998;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        position: relative;
    }

    .forgot-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: #3b5998;
        transition: width 0.3s ease;
    }

    .forgot-link:hover {
        color: #2d4373;
    }

    .forgot-link:hover::after {
        width: 100%;
    }

    .btn-login {
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
    }

    .btn-login::before {
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

    .btn-login:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 89, 152, 0.5);
    }

    .btn-login:active {
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

    .register-link {
        text-align: center;
        color: #666;
        font-size: 0.9rem;
        animation: fadeIn 0.8s ease-out 1s backwards;
    }

    .register-link a {
        color: #3b5998;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
    }

    .register-link a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: #3b5998;
        transition: width 0.3s ease;
    }

    .register-link a:hover {
        color: #2d4373;
    }

    .register-link a:hover::after {
        width: 100%;
    }

    @media (max-width: 768px) {
        .login-card {
            flex-direction: column;
        }

        .login-left {
            padding: 40px 30px;
        }

        .login-right {
            padding: 40px 30px;
        }

        .login-left h2 {
            font-size: 1.5rem;
        }

        .login-features {
            margin-top: 30px;
        }
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-left">
            <div class="login-left-content">
                <div class="login-logo">
                    <i class="fas fa-building" style="font-size: 2rem; color: #3b5998;"></i>
                </div>
                <h2>Bienvenue sur Jare industrie</h2>
                <p>Gérez vos projets immobiliers en toute simplicité avec notre plateforme intégrée.</p>
                
                <div class="login-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Gestion complète des souscriptions</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Suivi des paiements en temps réel</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Espace client sécurisé</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-right">
            <div class="login-header">
                <h3>Connexion Client</h3>
                <p>Accédez à votre espace personnel</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

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
                            autofocus
                            placeholder="exemple@email.com"
                        >
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

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
                            autocomplete="current-password"
                            placeholder="••••••••"
                        >
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="remember-forgot">
                    <div class="form-check">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            name="remember" 
                            id="remember" 
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <label class="form-check-label" for="remember">
                            Se souvenir de moi
                        </label>
                    </div>
                    
                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            Mot de passe oublié?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-login">
                    <span style="position: relative; z-index: 1;">Se connecter</span>
                </button>

                <div class="divider">
                    <span>OU</span>
                </div>

                <div class="register-link">
                    Pas encore de compte? <a href="{{ route('register') }}">S'inscrire</a>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
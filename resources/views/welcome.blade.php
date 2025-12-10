@extends('layouts.guest')

@section('content')
<style>
    /* Welcome page specific styles */
    .welcome-hero {
        background-image: url('{{ asset('bgwelcome.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: white;
        padding: 120px 5% 80px;
        text-align: center;
        position: relative;
        height: 500px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .welcome-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1;
    }

    .welcome-hero::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(59, 89, 152, 0.1) 0%, transparent 70%);
        animation: pulse 8s ease-in-out infinite;
        z-index: 1;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 900px;
        margin: 0 auto;
        animation: fadeInUp 1s ease-out 0.3s backwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero-content h1 {
        font-size: 2rem;
        margin-bottom: 2rem;
        font-weight: normal;
        line-height: 1.5;
        animation: fadeInUp 1s ease-out 0.5s backwards;
    }

    .hero-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        animation: fadeInUp 1s ease-out 0.7s backwards;
    }

    .btn-white {
        background: white;
        color: #333;
        padding: 0.7rem 2rem;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .btn-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        background: #f8f9fa;
    }

    .btn-outline {
        background: transparent;
        color: white;
        padding: 0.7rem 2rem;
        border: 2px solid white;
        border-radius: 3px;
        cursor: pointer;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-outline::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: white;
        transition: left 0.3s ease;
        z-index: -1;
    }

    .btn-outline:hover::before {
        left: 0;
    }

    .btn-outline:hover {
        color: #333;
        transform: translateY(-2px);
    }

    .features {
        padding: 60px 5%;
        background: white;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .feature-card {
        text-align: center;
        padding: 2rem 1rem;
        background: white;
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        animation: fadeInUp 0.6s ease-out backwards;
        cursor: pointer;
    }

    .feature-card:nth-child(1) { animation-delay: 0.1s; }
    .feature-card:nth-child(2) { animation-delay: 0.2s; }
    .feature-card:nth-child(3) { animation-delay: 0.3s; }
    .feature-card:nth-child(4) { animation-delay: 0.4s; }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        border-color: #3b5998;
    }

    .feature-icon {
        width: 150px;
        height: 150px;
        margin: 0 auto 1rem;
        background: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        transition: transform 0.4s ease;
    }

    .feature-card:hover .feature-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .feature-card h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #2F537C;
        transition: color 0.3s ease;
    }

    .feature-card:hover h3 {
        color: #3b5998;
    }

    .feature-card p {
        font-size: 1rem;
        color: #666;
        line-height: 1.4;
        transition: color 0.3s ease;
    }

    .feature-card:hover p {
        color: #333;
    }

    .clients {
        padding: 60px 5%;
        text-align: left;
        background: linear-gradient(to bottom, white, #f8f9fa);
        animation: fadeIn 1s ease-out;
    }

    .clients-container {
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
    }

    .clients h1 {
        margin-bottom: 2rem;
        font-size: 1.5rem;
        color: #333;
        font-weight: 600;
        animation: fadeInUp 0.6s ease-out;
    }

    .clients-logos {
        display: flex;
        justify-content: left;
        align-items: center;
        gap: 3rem;
        flex-wrap: wrap;
    }

    .client-logo {
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeInUp 0.6s ease-out backwards;
        transition: all 0.3s ease;
    }

    .client-logo:nth-child(1) { animation-delay: 0.1s; }
    .client-logo:nth-child(2) { animation-delay: 0.2s; }
    .client-logo:nth-child(3) { animation-delay: 0.3s; }
    .client-logo:nth-child(4) { animation-delay: 0.4s; }
    .client-logo:nth-child(5) { animation-delay: 0.5s; }

    .client-logo:hover {
        transform: scale(1.1);
    }

    .client-logo img {
        max-height: 100%;
        max-width: 120px;
        object-fit: contain;
        opacity: 0.5;
        transition: all 0.3s ease;
    }

    .client-logo:hover img {
        opacity: 1;
    }

    @media (max-width: 968px) {
        .features-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 1.5rem;
        }

        .features-grid {
            grid-template-columns: 1fr;
        }

        .hero-buttons {
            flex-direction: column;
        }

        .clients-logos {
            gap: 1.5rem;
        }
    }
</style>

<div class="welcome-hero">
    <div class="hero-content">
        <h1>Plateforme de gestion immobilière intégrée : souscription, suivi des paiements et gestion des attributions de lots.</h1>
        <div class="hero-buttons">
            <a href="{{ route('login') }}" class="btn-outline">SE CONNECTER</a>
            <a href="{{ route('register') }}" class="btn-white">S'INSCRIRE</a>
        </div>
    </div>
</div>

<section class="features">
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon"><img src="{{ asset('image 1.png') }}" alt="Gestion des souscriptions" style="width: 80px; height: 80px; object-fit: contain;"></div>
            <h3>Gestion des projets</h3>
            <p>Créez, suivez et attribuez vos logements.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><img src="{{ asset('image 1 (2).png') }}" alt="Suivi des paiements" style="width: 80px; height: 80px; object-fit: contain;"></div>
            <h3>Suivi comptable</h3>
            <p>Gérez les paiements, reçus et échéanciers.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><img src="{{ asset('image 1 (3).png') }}" alt="Gestion des dossiers" style="width: 80px; height: 80px; object-fit: contain;"></div>
            <h3>Fiches clients</h3>
            <p>Centralisez les informations et documents de vos clients.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><img src="{{ asset('image 1 (4).png') }}" alt="Attribution des lots" style="width: 80px; height: 80px; object-fit: contain;"></div>
            <h3>Validation interne</h3>
            <p>Automatisez les approbations et les attestations.</p>
        </div>
    </div>
</section>

<section class="clients">
    <div class="clients-container">
        <h1>Nos clients</h1>
        <div class="clients-logos">
            <div class="client-logo">
                <img src="{{ asset('entreprise/image 2.png') }}" alt="Client 1">
            </div>
            <div class="client-logo">
                <img src="{{ asset('entreprise/image 3.png') }}" alt="Client 2">
            </div>
            <div class="client-logo">
                <img src="{{ asset('entreprise/image 4.png') }}" alt="Client 3">
            </div>
            <div class="client-logo">
                <img src="{{ asset('entreprise/image 5.png') }}" alt="Client 4">
            </div>
            <div class="client-logo">
                <img src="{{ asset('entreprise/image 6.png') }}" alt="Client 5">
            </div>
        </div>
    </div>
</section>
@endsection
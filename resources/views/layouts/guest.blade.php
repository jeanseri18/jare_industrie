<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Jarel Instrudie') }} - Espace Client</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }

        /* Guest Navigation */
        .guest-nav {
            background: white;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .nav-logo {
            height: 45px;
            width: auto;
            transition: transform 0.3s ease;
        }

        .nav-logo:hover {
            transform: scale(1.05) rotate(2deg);
        }

        .nav-brand-text {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-brand-text .brand-highlight {
            color: #3b5998;
        }

        /* Navigation Links */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
            list-style: none;
        }

        .nav-menu li {
            animation: fadeInUp 0.6s ease-out backwards;
        }

        .nav-menu li:nth-child(1) { animation-delay: 0.1s; }
        .nav-menu li:nth-child(2) { animation-delay: 0.2s; }
        .nav-menu li:nth-child(3) { animation-delay: 0.3s; }
        .nav-menu li:nth-child(4) { animation-delay: 0.4s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .nav-link {
            padding: 0.6rem 1.2rem;
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            font-size: 0.95rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
        }

        .nav-link i {
            font-size: 1rem;
        }

        .nav-link:hover {
            color: #3b5998;
            background: rgba(59, 89, 152, 0.08);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            width: 0;
            height: 2px;
            background: #3b5998;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 80%;
        }

        /* CTA Buttons */
        .nav-cta {
            display: flex;
            gap: 0.8rem;
            align-items: center;
        }

        .btn-ghost {
            padding: 0.6rem 1.5rem;
            background: transparent;
            color: #3b5998;
            border: 2px solid #3b5998;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-ghost:hover {
            background: #3b5998;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 89, 152, 0.3);
        }

        .btn-primary {
            padding: 0.6rem 1.5rem;
            background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(59, 89, 152, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
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

        .btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 89, 152, 0.4);
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            padding: 0.5rem;
            background: transparent;
            border: none;
            cursor: pointer;
            color: #64748b;
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }

        .mobile-menu-btn:hover {
            color: #3b5998;
        }

        /* Mobile Menu */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .mobile-menu.open {
            max-height: 500px;
        }

        .mobile-nav-content {
            padding: 1.5rem;
        }

        .mobile-nav-links {
            list-style: none;
            margin-bottom: 1.5rem;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.9rem 1rem;
            color: #64748b;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .mobile-nav-link:hover {
            background: rgba(59, 89, 152, 0.08);
            color: #3b5998;
        }

        .mobile-nav-cta {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        /* Page Content Wrapper */
        .content-wrapper {
            min-height: calc(100vh - 70px);
            display: flex;
            flex-direction: column;
        }

        /* Footer */
        .guest-footer {
            background: #1a1a1a;
            color: white;
            padding: 3rem 1.5rem 1.5rem;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h4 {
            color: white;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.6rem;
        }

        .footer-links a {
            color: #aaa;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-social {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: #3b5998;
            transform: translateY(-3px);
        }

        .footer-bottom {
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            color: #aaa;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .nav-menu {
                display: none;
            }

            .nav-cta {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .mobile-menu {
                display: block;
            }
        }

        @media (max-width: 640px) {
            .nav-container {
                padding: 0 1rem;
                height: 65px;
            }

            .nav-logo {
                height: 40px;
            }

            .nav-brand-text {
                font-size: 1.1rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }

        /* Loading Animation */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .page-loader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f4f6;
            border-top-color: #3b5998;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Page Loader -->
    <div class="page-loader" id="pageLoader">
        <div class="loader-spinner"></div>
    </div>

    <div class="min-h-screen">
        <!-- Guest Navigation -->
        <nav class="guest-nav">
            <div class="nav-container">
                <div class="nav-brand">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('LOGO.png') }}" alt="Jarel Instrudie" class="nav-logo">
                    </a>
                 
                </div>

                <!-- Desktop Menu -->
                <ul class="nav-menu">
                    <li>
                        <a href="{{ url('/') }}" class="nav-link">
                            <span>Accueil</span>
                        </a>
                    </li>
                    <li>
                        <a href="#services" class="nav-link">
                            <span>Operateur</span>
                        </a>
                    </li>
                    <li>
                        <a href="#about" class="nav-link">
                            <span>Commercial</span>
                        </a>
                    </li>
                    <li>
                        <a href="#contact" class="nav-link">
                            <span>Comptable</span>
                        </a>
                    </li>
                     <li>
                        <a href="#contact" class="nav-link">
                            <span>DG</span>
                        </a>
                    </li>
                </ul>

                <!-- CTA Buttons -->
                <div class="nav-cta">
          
                    <a href="" class="btn-primary">
                        <span style="position: relative; z-index: 1;">Contact</span>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="mobile-menu-btn" onclick="document.querySelector('.mobile-menu').classList.toggle('open')">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div class="mobile-menu">
                <div class="mobile-nav-content">
                    <ul class="mobile-nav-links">
                        <li>
                            <a href="{{ url('/') }}" class="mobile-nav-link">
                                <i class="fas fa-home"></i>
                                <span>Accueil</span>
                            </a>
                        </li>
                        <li>
                            <a href="#services" class="mobile-nav-link">
                                <i class="fas fa-briefcase"></i>
                                <span>Services</span>
                            </a>
                        </li>
                        <li>
                            <a href="#about" class="mobile-nav-link">
                                <i class="fas fa-info-circle"></i>
                                <span>À propos</span>
                            </a>
                        </li>
                        <li>
                            <a href="#contact" class="mobile-nav-link">
                                <i class="fas fa-envelope"></i>
                                <span>Contact</span>
                            </a>
                            </li>
                    </ul>
                    <div class="mobile-nav-cta">
                        <a href="{{ route('login') }}" class="btn-ghost" style="justify-content: center;">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Connexion</span>
                        </a>
                        <a href="{{ route('register') }}" class="btn-primary" style="justify-content: center;">
                            <i class="fas fa-user-plus"></i>
                            <span style="position: relative; z-index: 1;">Inscription</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Page Content -->
            <main>
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="guest-footer">
                <div class="footer-container">
                    <div class="footer-content">
                        <div class="footer-section">
                            <h4>Jare industrie</h4>
                            <p style="color: #aaa; font-size: 0.9rem; line-height: 1.6;">
                                Votre plateforme de gestion immobilière intégrée pour une gestion simplifiée et efficace.
                            </p>
                            <div class="footer-social">
                                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="footer-section">
                            <h4>Navigation</h4>
                            <ul class="footer-links">
                                <li><a href="{{ url('/') }}">Accueil</a></li>
                                <li><a href="#services">Services</a></li>
                                <li><a href="#about">À propos</a></li>
                                <li><a href="#contact">Contact</a></li>
                            </ul>
                        </div>
                        <div class="footer-section">
                            <h4>Ressources</h4>
                            <ul class="footer-links">
                                <li><a href="#">Documentation</a></li>
                                <li><a href="#">Guide utilisateur</a></li>
                                <li><a href="#">FAQ</a></li>
                                <li><a href="#">Support</a></li>
                            </ul>
                        </div>
                        <div class="footer-section">
                            <h4>Contact</h4>
                            <ul class="footer-links">
                                <li><i class="fas fa-envelope" style="margin-right: 8px; color: #3b5998;"></i> contact@immogest.ci</li>
                                <li><i class="fas fa-phone" style="margin-right: 8px; color: #3b5998;"></i> +225 01 XX XX XX XX</li>
                                <li><i class="fas fa-map-marker-alt" style="margin-right: 8px; color: #3b5998;"></i> Abidjan, Plateau</li>
                            </ul>
                        </div>
                    </div>
                    <div class="footer-bottom">
                        <p>&copy; {{ date('Y') }} Jare industrie. Tous droits réservés.</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Hide page loader when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('pageLoader').classList.add('hidden');
            }, 500);
        });

        // Close mobile menu when clicking a link
        document.querySelectorAll('.mobile-nav-link').forEach(link => {
            link.addEventListener('click', function() {
                document.querySelector('.mobile-menu').classList.remove('open');
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && document.querySelector(href)) {
                    e.preventDefault();
                    document.querySelector(href).scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>
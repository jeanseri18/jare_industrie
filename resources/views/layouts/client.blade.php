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
        }

        /* Navigation moderne */
        .modern-nav {
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
            transform: scale(1.05);
        }

        .nav-links {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            list-style: none;
        }

        .nav-link {
            padding: 0.6rem 1.2rem;
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            font-size: 0.95rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: fadeInUp 0.6s ease-out backwards;
        }

        .nav-link:nth-child(1) { animation-delay: 0.1s; }
        .nav-link:nth-child(2) { animation-delay: 0.2s; }
        .nav-link:nth-child(3) { animation-delay: 0.3s; }
        .nav-link:nth-child(4) { animation-delay: 0.4s; }

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

        .nav-link i {
            font-size: 1rem;
            transition: transform 0.3s ease;
        }

        .nav-link:hover {
            color: #3b5998;
            background: rgba(59, 89, 152, 0.08);
        }

        .nav-link:hover i {
            transform: scale(1.2);
        }

        .nav-link.active {
            color: white;
            background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
            box-shadow: 0 4px 12px rgba(59, 89, 152, 0.3);
        }

        .nav-link.active i {
            color: white;
        }

        /* Badge de notification */
        .notification-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #ef4444;
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: 600;
            animation: pulse-badge 2s ease-in-out infinite;
        }

        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* User dropdown */
        .user-dropdown {
            position: relative;
            animation: fadeIn 0.6s ease-out 0.5s backwards;
        }

        .user-trigger {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.5rem 1rem;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-trigger:hover {
            border-color: #3b5998;
            background: white;
            box-shadow: 0 4px 12px rgba(59, 89, 152, 0.15);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #1e293b;
        }

        .user-role {
            font-size: 0.75rem;
            color: #64748b;
        }

        .dropdown-arrow {
            color: #64748b;
            transition: transform 0.3s ease;
        }

        .user-dropdown.open .dropdown-arrow {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .user-dropdown.open .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.9rem 1.2rem;
            color: #475569;
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .dropdown-item:hover {
            background: #f8fafc;
            color: #3b5998;
            border-left-color: #3b5998;
        }

        .dropdown-item i {
            width: 20px;
            font-size: 1rem;
        }

        .dropdown-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 0.5rem 0;
        }

        /* Mobile menu */
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

        .mobile-nav-links {
            padding: 1rem;
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
        }

        .mobile-nav-link:hover,
        .mobile-nav-link.active {
            background: rgba(59, 89, 152, 0.08);
            color: #3b5998;
        }

        /* Page header */
        .page-header {
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            animation: fadeIn 0.6s ease-out 0.3s backwards;
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        .header-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .header-title i {
            color: #3b5998;
            font-size: 1.5rem;
        }

        /* Main content */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
            animation: fadeInUp 0.6s ease-out 0.4s backwards;
        }

        /* Quick stats cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out backwards;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .nav-links {
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
            }

            .user-info {
                display: none;
            }

            .header-container {
                padding: 1.5rem 1rem;
            }

            .header-title {
                font-size: 1.4rem;
            }

            .main-content {
                padding: 1.5rem 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
    <div class="min-h-screen">
        <!-- Navigation moderne -->
        <nav class="modern-nav">
            <div class="nav-container">
                <div class="nav-brand">
                    <a href="{{ route('client.dashboard') }}">
                        <img src="{{ asset('LOGO.png') }}" alt="Jarel Instrudie" class="nav-logo">
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <ul class="nav-links">
                    <li>
                        <a href="{{ route('client.dashboard') }}" class="nav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Accueil</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.documents') }}" class="nav-link {{ request()->routeIs('client.documents') ? 'active' : '' }}">
                            <i class="fas fa-folder"></i>
                            <span>Documents</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.paiements') }}" class="nav-link {{ request()->routeIs('client.paiements') ? 'active' : '' }}">
                            <i class="fas fa-credit-card"></i>
                            <span>Paiements</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.notifications') }}" class="nav-link {{ request()->routeIs('client.notifications') ? 'active' : '' }}" style="position: relative;">
                            <i class="fas fa-bell"></i>
                            <span>Notifications</span>
                            <span class="notification-badge">3</span>
                        </a>
                    </li>
                </ul>

                <!-- User Dropdown -->
                <div class="user-dropdown" onclick="this.classList.toggle('open')">
                    <div class="user-trigger">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                            <div class="user-role">Client</div>
                        </div>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    
                    <div class="dropdown-menu">
                        <a href="{{ route('client.profile') }}" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>Mon Profil</span>
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-cog"></i>
                            <span>Paramètres</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('client.logout') }}">
                            @csrf
                            <a href="{{ route('client.logout') }}" class="dropdown-item" onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Déconnexion</span>
                            </a>
                        </form>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button class="mobile-menu-btn" onclick="document.querySelector('.mobile-menu').classList.toggle('open')">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div class="mobile-menu">
                <div class="mobile-nav-links">
                    <a href="{{ route('client.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Accueil</span>
                    </a>
                    <a href="{{ route('client.documents') }}" class="mobile-nav-link {{ request()->routeIs('client.documents') ? 'active' : '' }}">
                        <i class="fas fa-folder"></i>
                        <span>Documents</span>
                    </a>
                    <a href="{{ route('client.paiements') }}" class="mobile-nav-link {{ request()->routeIs('client.paiements') ? 'active' : '' }}">
                        <i class="fas fa-credit-card"></i>
                        <span>Paiements</span>
                    </a>
                    <a href="{{ route('client.notifications') }}" class="mobile-nav-link {{ request()->routeIs('client.notifications') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('client.profile') }}" class="mobile-nav-link">
                        <i class="fas fa-user"></i>
                        <span>Mon Profil</span>
                    </a>
                    <form method="POST" action="{{ route('client.logout') }}">
                        @csrf
                        <a href="{{ route('client.logout') }}" class="mobile-nav-link" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Déconnexion</span>
                        </a>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Page Header -->
        @if (isset($header))
            <header class="page-header">
                <div class="header-container">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <script>
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.user-dropdown');
            if (dropdown && !dropdown.contains(event.target)) {
                dropdown.classList.remove('open');
            }
        });

        // Close mobile menu when clicking a link
        document.querySelectorAll('.mobile-nav-link').forEach(link => {
            link.addEventListener('click', function() {
                document.querySelector('.mobile-menu').classList.remove('open');
            });
        });
    </script>
</body>
</html>
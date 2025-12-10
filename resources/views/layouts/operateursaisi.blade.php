<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Jarel Instrudie') }} - Espace Opérateur de Saisie</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Figtree', sans-serif; background: #f5f7fa; }
        .operateur-nav { background: #ea580c; box-shadow: 0 2px 20px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000; }
        .nav-container { max-width: 1400px; margin: 0 auto; padding: 0 1.5rem; display: flex; justify-content: space-between; align-items: center; height: 70px; }
        .nav-brand { display: flex; align-items: center; gap: 1rem; color: white; }
        .nav-logo { height: 45px; width: auto; }
        .nav-links { display: flex; gap: 0.5rem; align-items: center; list-style: none; }
        .nav-link { padding: 0.6rem 1.2rem; text-decoration: none; color: #fed7aa; font-weight: 500; font-size: 0.95rem; border-radius: 8px; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; }
        .nav-link:hover { color: white; background: rgba(255, 255, 255, 0.1); }
        .nav-link.active { color: white; background: #f97316; }
        .user-dropdown { position: relative; }
        .user-trigger { display: flex; align-items: center; gap: 0.8rem; padding: 0.5rem 1rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 50px; cursor: pointer; transition: all 0.3s ease; color: white; }
        .user-trigger:hover { background: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.3); }
        .user-avatar { width: 35px; height: 35px; border-radius: 50%; background: #f97316; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem; }
        .main-content { max-width: 1400px; margin: 2rem auto; padding: 0 1.5rem; }
        .page-header { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 2rem; }
        .page-title { font-size: 2rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem; }
        .page-subtitle { color: #64748b; font-size: 1.1rem; }
    </style>
</head>
<body>
    <nav class="operateur-nav">
        <div class="nav-container">
            <div class="nav-brand">
                <img src="{{ asset('LOGO.png') }}" alt="Logo" class="nav-logo">
                <div>
                    <div style="font-weight: 600; font-size: 1.1rem;">Jarel Instrudie</div>
                    <div style="font-size: 0.8rem; opacity: 0.8;">Espace Opérateur de Saisie</div>
                </div>
            </div>
            
            <ul class="nav-links">
                <li><a href="{{ route('operateur.dashboard') }}" class="nav-link {{ request()->routeIs('operateur.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                <li><a href="#" class="nav-link"><i class="fas fa-edit"></i> Saisie</a></li>
                <li><a href="#" class="nav-link"><i class="fas fa-database"></i> Données</a></li>
            </ul>

            <div class="user-dropdown">
                <div class="user-trigger" onclick="toggleDropdown()">
                    <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    <div style="color: white;">
                        <div style="font-weight: 500; font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                        <div style="font-size: 0.75rem; opacity: 0.8;">{{ ucfirst(Auth::user()->role) }}</div>
                    </div>
                    <i class="fas fa-chevron-down" style="color: white;"></i>
                </div>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">@yield('title', 'Tableau de bord')</h1>
            <p class="page-subtitle">@yield('subtitle', 'Espace opérateur de saisie')</p>
        </div>
        @yield('content')
    </main>

    <script>function toggleDropdown() {}</script>
    @stack('scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Chef Commercial - Jare Industries')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; background: #f5f7fa; overflow-x: hidden; }
        /* Sidebar */
        .sidebar { position: fixed; left: 0; top: 0; bottom: 0; width: 280px; background: linear-gradient(180deg, #003d82 0%, #002457 100%); padding: 20px 0; overflow-y: auto; z-index: 1000; }
        .logo-container { background: white; margin: 0 20px 30px; padding: 20px; border-radius: 8px; text-align: center; }
        .logo-container img { max-width: 100%; height: auto; }
        .nav-menu { list-style: none; padding: 0; margin: 0; }
        .nav-link { display: flex; align-items: center; padding: 14px 20px; color: white; text-decoration: none; transition: all 0.3s ease; font-size: 15px; border-left: 3px solid transparent; }
        .nav-link:hover, .nav-link.active { background: rgba(255, 255, 255, 0.1); border-left-color: #4CAF50; color: white; }
        .nav-link i { margin-right: 12px; font-size: 18px; width: 24px; text-align: center; }
        .sidebar-footer { position: absolute; bottom: 20px; left: 20px; color: rgba(255, 255, 255, 0.6); font-size: 12px; }
        /* Top Bar */
        .top-bar { position: fixed; top: 0; left: 280px; right: 0; height: 70px; background: white; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; z-index: 999; }
        .search-container { flex: 1; max-width: 500px; }
        .search-box { position: relative; }
        .search-box input { width: 100%; padding: 10px 20px 10px 45px; border: 1px solid #e5e7eb; border-radius: 25px; font-size: 14px; outline: none; transition: all 0.3s ease; }
        .search-box input:focus { border-color: #003d82; box-shadow: 0 0 0 3px rgba(0, 61, 130, 0.1); }
        .search-box i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
        .top-bar-right { display: flex; align-items: center; gap: 20px; }
        .user-info { display: flex; align-items: center; gap: 10px; cursor: pointer; position: relative; }
        .user-name { font-size: 14px; color: #111827; font-weight: 500; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: #003d82; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; }
        .dropdown-menu-custom { position: absolute; top: 100%; right: 0; background: white; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); min-width: 200px; margin-top: 10px; display: none; z-index: 1001; }
        .dropdown-menu-custom.show { display: block; }
        .dropdown-item-custom { display: block; width: 100%; padding: 12px 16px; text-align: left; border: none; background: none; color: #111827; text-decoration: none; transition: background 0.2s; cursor: pointer; }
        .dropdown-item-custom:hover { background: #f3f4f6; }
        /* Main Content */
        .main-content { margin-left: 280px; margin-top: 70px; padding: 30px; min-height: calc(100vh - 70px); }
        /* Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); }
        .stat-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
        .stat-title { font-size: 14px; color: #6b7280; }
        .stat-value { font-size: 28px; font-weight: 700; color: #111827; }
        .badge-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        /* Table */
        .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .card-header { padding: 16px 20px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
        .card-body { padding: 20px; }
        .table thead th { font-size: 12px; text-transform: uppercase; color: #6b7280; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="logo-container">
            <img src="{{ asset('LOGO.png') }}" alt="Logo">
        </div>
        <ul class="nav-menu">
            <li><a class="nav-link {{ request()->routeIs('chef_commercial.dashboard') ? 'active' : '' }}" href="{{ route('chef_commercial.dashboard') }}"><i class="fa-solid fa-chart-line"></i> Tableau de bord</a></li>
            <li><a class="nav-link {{ request()->routeIs('chef_commercial.souscriptions.corrigees') ? 'active' : '' }}" href="{{ route('chef_commercial.souscriptions.corrigees') }}"><i class="fa-solid fa-list-check"></i> Liste des souscriptions corrigées</a></li>
            <li><a class="nav-link {{ request()->routeIs('chef_commercial.souscriptions.create') ? 'active' : '' }}" href="{{ route('chef_commercial.souscriptions.create') }}"><i class="fa-solid fa-plus-circle"></i> Nouvelle souscription</a></li>
        </ul>
        <div class="sidebar-footer">Jare Industries © {{ date('Y') }}</div>
    </aside>

    <header class="top-bar">
        <div class="search-container">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Rechercher...">
            </div>
        </div>
        <div class="top-bar-right">
            <div class="user-info" onclick="toggleUserMenu()">
                <div class="user-name">{{ Auth::user()->name ?? 'Utilisateur' }}</div>
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
                <div id="userMenu" class="dropdown-menu-custom">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item-custom"><i class="fa-solid fa-right-from-bracket me-2"></i> Déconnexion</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        @yield('content')
    </main>

    <script>
        function toggleUserMenu() { document.getElementById('userMenu').classList.toggle('show'); }
        document.addEventListener('click', function(e){ const m=document.getElementById('userMenu'); if(!e.target.closest('.user-info') && m && m.classList.contains('show')) m.classList.remove('show'); });
    </script>
</body>
</html>
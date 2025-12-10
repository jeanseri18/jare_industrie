<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Jare Industries')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f7fa;
            overflow-x: hidden;
        }

        /* Top Bar */
        .top-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 999;
        }

        .top-bar-left {
            display: flex;
            align-items: center;
        }

        .logo-container-top {
            display: flex;
            align-items: center;
        }

        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 20px;
            color: #6b7280;
            cursor: pointer;
            padding: 8px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            position: relative;
        }

        .user-name {
            font-size: 14px;
            color: #111827;
            font-weight: 500;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #003d82;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .dropdown-menu-custom {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            margin-top: 10px;
            display: none;
            z-index: 1001;
        }

        .dropdown-menu-custom.show {
            display: block;
        }

        .dropdown-item-custom {
            display: block;
            width: 100%;
            padding: 12px 16px;
            text-align: left;
            border: none;
            background: none;
            color: #111827;
            text-decoration: none;
            transition: background 0.2s;
            cursor: pointer;
        }

        .dropdown-item-custom:hover {
            background: #f3f4f6;
        }

        /* Main Content */
        .main-content {
            margin-left: 0;
            margin-top: 70px;
            padding: 30px;
            min-height: calc(100vh - 70px);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .stat-title {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .icon-blue { background: #dbeafe; color: #2563eb; }
        .icon-orange { background: #fed7aa; color: #ea580c; }
        .icon-green { background: #d1fae5; color: #059669; }
        .icon-purple { background: #e9d5ff; color: #9333ea; }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        /* Data Table */
        .data-table-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            text-align: left;
            padding: 12px;
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            border-bottom: 2px solid #e5e7eb;
        }

        tbody td {
            padding: 16px 12px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            color: #111827;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .progress-bar-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .progress-bar-container {
            flex: 1;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .progress-green { background: #10b981; }
        .progress-orange { background: #f59e0b; }
        .progress-red { background: #ef4444; }

        .progress-text {
            font-weight: 600;
            min-width: 45px;
        }

        .evolution {
            font-weight: 600;
        }

        .evolution-positive { color: #10b981; }
        .evolution-negative { color: #ef4444; }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
            
            .top-bar {
                left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="top-bar-left">
            <div class="logo-container-top">
                <img src="{{ asset('LOGO.png') }}" alt="Jarel Instrudie" style="height: 40px;">
            </div>
        </div>
        <div class="top-bar-right">
            <button class="notification-btn">
                <i class="fas fa-bell"></i>
            </button>
            <div class="user-info" onclick="toggleDropdown()">
                <span class="user-name">{{ Auth::user()->name }}</span>
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="dropdown-menu-custom" id="userDropdown">
                

                    <hr style="margin: 0; border-color: #e5e7eb;">
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="dropdown-item-custom" style="width: 100%;">
                            <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // Fermer le dropdown si on clique ailleurs
        document.addEventListener('click', function(event) {
            const userInfo = document.querySelector('.user-info');
            const dropdown = document.getElementById('userDropdown');
            
            if (!userInfo.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Auto-hide alerts après 5 secondes
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    @stack('scripts')
</body>
</html>
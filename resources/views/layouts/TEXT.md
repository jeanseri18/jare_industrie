<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Jare Industries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 280px;
            background: linear-gradient(180deg, #003d82 0%, #002457 100%);
            padding: 20px 0;
            overflow-y: auto;
            z-index: 1000;
        }

        .logo-container {
            background: white;
            margin: 0 20px 30px;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .logo-container img {
            max-width: 100%;
            height: auto;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            margin: 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 15px;
            border-left: 3px solid transparent;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #4CAF50;
        }

        .nav-link i {
            margin-right: 12px;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 20px;
            left: 20px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 12px;
        }

        /* Top Bar */
        .top-bar {
            position: fixed;
            top: 0;
            left: 280px;
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

        .search-container {
            flex: 1;
            max-width: 500px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 10px 20px 10px 45px;
            border: 1px solid #e5e7eb;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            border-color: #003d82;
            box-shadow: 0 0 0 3px rgba(0, 61, 130, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
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
            cursor: pointer;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
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
            .sidebar {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .top-bar {
                left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-container">
            <svg width="120" height="60" viewBox="0 0 120 60" xmlns="http://www.w3.org/2000/svg">
                <rect x="30" y="15" width="12" height="30" fill="#dc2626" rx="2"/>
                <rect x="45" y="10" width="12" height="35" fill="#dc2626" rx="2"/>
                <rect x="60" y="20" width="12" height="25" fill="#dc2626" rx="2"/>
                <rect x="75" y="12" width="12" height="33" fill="#dc2626" rx="2"/>
                <text x="60" y="55" font-family="Arial, sans-serif" font-size="16" font-weight="bold" fill="#003d82" text-anchor="middle">JARE</text>
            </svg>
            <div style="font-size: 10px; color: #dc2626; margin-top: 5px;">PROMOTEUR IMMOBILIER AGREE</div>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Pilotage financier</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Décisions DG</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-gavel"></i>
                    <span>Suivi des projets</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Suivi des équipes</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-user-friends"></i>
                    <span>Gestion des clients</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-handshake"></i>
                    <span>Gestion des mutuelles</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-building"></i>
                    <span>Administration générale</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Rapports</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Paramètres</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            jaresindustries©2025
        </div>
    </div>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="search-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Rechercher...">
            </div>
        </div>
        <div class="top-bar-right">
            <button class="notification-btn">
                <i class="fas fa-bell"></i>
            </button>
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Total encaissé</span>
                    <div class="stat-icon icon-blue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="stat-value">58,2 M FCFA</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Frais de dossier</span>
                    <div class="stat-icon icon-orange">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                </div>
                <div class="stat-value">3,2M FCFA</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Apports initiaux</span>
                    <div class="stat-icon icon-green">
                        <i class="fas fa-money-bill"></i>
                    </div>
                </div>
                <div class="stat-value">58,2 M FCFA</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Paiements projet</span>
                    <div class="stat-icon icon-purple">
                        <i class="fas fa-credit-card"></i>
                    </div>
                </div>
                <div class="stat-value">27,5 M FCFA</div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="data-table-container">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Indicateur</th>
                            <th>Montant encaissé</th>
                            <th>Montant attendu</th>
                            <th>Progression</th>
                            <th>Évolution</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Total encaissé</strong></td>
                            <td>58 200 000 FCFA</td>
                            <td>80 000 000 FCFA</td>
                            <td>
                                <div class="progress-bar-cell">
                                    <div class="progress-bar-container">
                                        <div class="progress-fill progress-green" style="width: 73%"></div>
                                    </div>
                                    <span class="progress-text">73 %</span>
                                </div>
                            </td>
                            <td><span class="evolution evolution-positive">+11 %</span></td>
                        </tr>
                        <tr>
                            <td><strong>Frais de dossier</strong></td>
                            <td>3 200 000</td>
                            <td>3 800 000</td>
                            <td>
                                <div class="progress-bar-cell">
                                    <div class="progress-bar-container">
                                        <div class="progress-fill progress-green" style="width: 85%"></div>
                                    </div>
                                    <span class="progress-text">85 %</span>
                                </div>
                            </td>
                            <td><span class="evolution evolution-positive">+8 %</span></td>
                        </tr>
                        <tr>
                            <td><strong>Apports initiaux</strong></td>
                            <td>45 800 000</td>
                            <td>58 000 000</td>
                            <td>
                                <div class="progress-bar-cell">
                                    <div class="progress-bar-container">
                                        <div class="progress-fill progress-orange" style="width: 79%"></div>
                                    </div>
                                    <span class="progress-text">79 %</span>
                                </div>
                            </td>
                            <td><span class="evolution evolution-positive">+9 %</span></td>
                        </tr>
                        <tr>
                            <td><strong>Paiements projet</strong></td>
                            <td>27 500 000</td>
                            <td>45 000 000</td>
                            <td>
                                <div class="progress-bar-cell">
                                    <div class="progress-bar-container">
                                        <div class="progress-fill progress-red" style="width: 61%"></div>
                                    </div>
                                    <span class="progress-text">61 %</span>
                                </div>
                            </td>
                            <td><span class="evolution evolution-positive">+12 %</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
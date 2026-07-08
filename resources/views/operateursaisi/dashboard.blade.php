@extends('layouts.operateur')

@section('content')
    <style>
        .app-user-avatar-sm { width: 2rem; height: 2rem; font-size: 0.75rem; }
        .app-user-avatar-lg { width: 3rem; height: 3rem; font-size: 1rem; }

        .operateur-dashboard {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
        }

        .operateur-dashboard .header {
            background: #000;
            color: white;
            padding: 2rem 1.5rem 2.5rem;
            width: 100%;
            margin: 0;
        }

        .operateur-dashboard .header-inner {
            max-width: 1400px;
            margin: 0 auto;
        }

        .operateur-dashboard .header-title {
            font-size: 1.75rem;
            font-weight: 600;
            margin: 0 0 1.5rem;
            line-height: 1.3;
        }

        .operateur-dashboard .profile {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .operateur-dashboard .profile-pic {
            display: none;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
        }

        .profile-label {
            font-size: 12px;
            opacity: 0.9;
        }

        .profile-name {
            font-size: 16px;
            font-weight: 600;
        }

        .stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            width: 100%;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 15px;
            background-color: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 15px 20px;
            border-radius: 8px;
            min-width: 180px;
            flex: 1;
        }

        .stat-icon {
            font-size: 100px;
            opacity: 0.9;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .stat-icon img {
            width: 132px;
            height: 132px;
            object-fit: cover;
        }

        .stat-content {
            display: flex;
            flex-direction: column;
        }

        .stat-label {
            font-size: 13px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 60px;
            font-weight: 700;
            color: white;
        }

        .stat-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 6px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .stat-circle::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 6px solid white;
            border-right-color: transparent;
            border-bottom-color: transparent;
            transform: rotate(-45deg);
        }

        .stat-chart {
            display: flex;
            align-items: flex-end;
            gap: 3px;
            height: 40px;
        }

        .chart-bar {
            width: 8px;
            background-color: white;
            border-radius: 2px;
        }

        .operateur-dashboard .main-content {
            padding: 1.5rem !important;
            width: 100% !important;
            max-width: 1400px !important;
            margin: 0 auto !important;
        }
        
        .operateur-dashboard .toolbar,
        .operateur-dashboard .section-header {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 20px;
        }

        .search-box {
            display: flex;
            align-items: center;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 15px;
            flex: 1;
            max-width: 600px;
        }

        .search-box input {
            border: none;
            outline: none;
            flex: 1;
            font-size: 14px;
        }

        .search-icon {
            color: #666;
            margin-left: 10px;
        }

        .btn-primary {
            background-color: #ff7200;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #e66500;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .filter-btn {
            background-color: white;
            border: 1px solid #ddd;
            padding: 8px 20px;
            border-radius: var(--ui-radius, 0.75rem);
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>

    <div class="operateur-dashboard">
    <div class="header">
        <div class="header-inner">
        <h1 class="header-title">Opératrice de saisie</h1>
        
        <div class="profile">
            <x-user-avatar :user="$user" size="lg" />
            <div class="profile-info">
                <span class="profile-label">Bienvenue</span>
                <span class="profile-name">{{ $user->name ?? $user->email }}</span>
            </div>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <img src="{{ asset('operateur/souscription 1.png') }}" alt="Total souscriptions" style="width: 32px; height: 32px;">
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total de souscriptions<br>créées</div>
                    <div class="stat-value">{{ $totalSouscriptions }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <img src="{{ asset('operateur/souscription 2.png') }}" alt="Souscriptions en attente" style="width: 32px; height: 32px;">
                </div>
                <div class="stat-content">
                    <div class="stat-label">Souscriptions en<br>attente de validation</div>
                    <div class="stat-value">{{ $souscriptionsEnAttente }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <img src="{{ asset('operateur/souscription 3.png') }}" alt="Souscriptions validées" style="width: 32px; height: 32px;">
                </div>
                <div class="stat-content">
                    <div class="stat-label">Souscriptions validées<br>par la comptabilité</div>
                    <div class="stat-value">{{ $souscriptionsValidees }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-circle">
                    <div class="stat-value" style="font-size: 24px;">{{ $tauxValidation }}%</div>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Taux de<br>validation :</div>
                    <div style="font-size: 18px; font-weight: 600;">{{ $tauxValidation }}%</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-chart">
                    <div class="chart-bar" style="height: 50%;"></div>
                    <div class="chart-bar" style="height: 70%;"></div>
                    <div class="chart-bar" style="height: 40%;"></div>
                    <div class="chart-bar" style="height: 85%;"></div>
                    <div class="chart-bar" style="height: 60%;"></div>
                    <div class="chart-bar" style="height: 100%;"></div>
                </div>
                <div class="stat-content">
                        <div class="stat-label">Nombre total créées<br>cette semaine</div>
                        <div class="stat-value" style="font-size: 28px;">{{ $souscriptionsCeMois }}</div>
                        <div style="font-size: 11px; color: rgba(255,255,255,0.8);">{{ $evolution > 0 ? '+' : '' }}{{ $evolution }}%</div>
                    </div>
            </div>
        </div>
        </div>
    </div>

    <div class="main-content">
        <div class="toolbar">
            <div class="search-box">
                <input type="text" placeholder="Rechercher un dossier">
                <!-- <span class="search-icon">
                    <img src="{{ asset('operateur/souscription 1.png') }}" alt="Rechercher" style="width: 16px; height: 16px;">
                </span> -->
            </div>
            <button class="btn-primary" onclick="window.location.href='{{ route('operateur.souscriptions.create') }}'">Nouvelle souscription</button>
        </div>

        <div class="section-header">
            <h2 class="section-title">Mes souscriptions</h2>
            <button class="filter-btn">Filtrer <img src="{{ asset('operateur/souscription 2.png') }}" alt="Filtrer" style="width: 16px; height: 16px; margin-left: 5px;"></button>
        </div>

        <div class="data-table-container">
            <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Réf. Souscription</th>
                        <th>Réf. Client</th>
                        <th>Nom du client</th>
                        <th>Projet</th>
                        <th>Type logement</th>
                        <th>Date de création</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($souscriptions as $souscription)
                        <tr>
                            <td>{{ $souscription->ref_souscription ?? 'N/A' }}</td>
                            <td>{{ $souscription->client->ref_client ?? 'N/A' }}</td>
                            <td>{{ $souscription->nom_prenom ?? 'N/A' }}</td>
                            <td>{{ $souscription->nom_programme ?? 'Projet non défini' }}</td>
                            <td>{{ $souscription->type_logement ?? 'N/A' }}</td>
                            <td>{{ $souscription->created_at->format('d/m/Y') }}</td>
                            <td>
                                @if($souscription->statut == 'valide')
                                    <span class="status-badge status-valide">Validé</span>
                                @elseif($souscription->statut == 'a_corriger')
                                    <span class="status-badge status-corriger">À corriger</span>
                                @elseif($souscription->statut == 'en_attente')
                                    <span class="status-badge status-attente">En attente</span>
                                @else
                                    <span class="status-badge status-attente">{{ ucfirst($souscription->statut) }}</span>
                                @endif
                            </td>
                            <td><button class="btn-voir" onclick='showSubscriptionDetails(@json($souscription))'>Voir</button></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #666;">
                                Aucune souscription trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>

    </div>{{-- .operateur-dashboard --}}

    <!-- Modal pour afficher les détails complets de la souscription -->
    <div id="subscriptionModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
        <div class="modal-content" style="background-color: white; margin: 5% auto; padding: 20px; border-radius: 8px; width: 80%; max-width: 800px; max-height: 80vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #f0f0f0;">
                <h2 style="margin: 0; color: #111827;">Détails complets de la souscription</h2>
                <span onclick="closeModal()" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            </div>
            
            <div id="modalContent">
                <!-- Les détails seront chargés ici -->
            </div>
        </div>
    </div>

    <style>
        .detail-group {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
        }
        
        .detail-group h3 {
            color: #111827;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 150px;
        }
        
        .detail-value {
            color: #212529;
            text-align: right;
            flex: 1;
            margin-left: 20px;
        }
        
        .modal-close-btn {
            background-color: #111827;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        
        .modal-close-btn:hover {
            background-color: #000;
        }
    </style>

    <script>
        function showSubscriptionDetails(souscription) {
            const modal = document.getElementById('subscriptionModal');
            const modalContent = document.getElementById('modalContent');
            
            // Formater la date
            const formatDate = (dateString) => {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('fr-FR');
            };
            
            // Formater le montant
            const formatMontant = (montant) => {
                if (!montant) return '0 F CFA';
                return new Intl.NumberFormat('fr-FR').format(montant) + ' F CFA';
            };
            
            modalContent.innerHTML = `
                <div class="detail-group">
                    <h3>Informations de la souscription</h3>
                    <div class="detail-row">
                        <span class="detail-label">Référence souscription:</span>
                        <span class="detail-value">${souscription.ref_souscription || 'N/A'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date de création:</span>
                        <span class="detail-value">${formatDate(souscription.created_at)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Statut:</span>
                        <span class="detail-value">${souscription.statut || 'N/A'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Programme:</span>
                        <span class="detail-value">${souscription.nom_programme || 'N/A'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Type de logement:</span>
                        <span class="detail-value">${souscription.type_logement || 'N/A'}</span>
                    </div>
                </div>

                <div class="detail-group">
                    <h3>Informations du client</h3>
                    <div class="detail-row">
                        <span class="detail-label">Référence client:</span>
                        <span class="detail-value">${souscription.client?.ref_client || 'N/A'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Nom complet:</span>
                        <span class="detail-value">${souscription.nom_prenom || 'N/A'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">${souscription.client?.email || 'N/A'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Téléphone:</span>
                        <span class="detail-value">${souscription.client?.telephone || 'N/A'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Catégorie client:</span>
                        <span class="detail-value">${souscription.client?.categorie_client || 'N/A'}</span>
                    </div>
                </div>

                <div class="detail-group">
                    <h3>Informations financières</h3>
                    <div class="detail-row">
                        <span class="detail-label">Valeur de souscription:</span>
                        <span class="detail-value">${formatMontant(souscription.valeur_souscription)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Apport initial:</span>
                        <span class="detail-value">${formatMontant(souscription.apport_initial)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Frais de souscription:</span>
                        <span class="detail-value">${formatMontant(souscription.frais_souscription)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Mode de paiement:</span>
                        <span class="detail-value">${souscription.mode_paiement || 'N/A'}</span>
                    </div>
                </div>

                <div class="detail-group">
                    <h3>Informations complémentaires</h3>
                    <div class="detail-row">
                        <span class="detail-label">Date de début:</span>
                        <span class="detail-value">${formatDate(souscription.date_debut)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date de fin:</span>
                        <span class="detail-value">${formatDate(souscription.date_fin)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Durée (mois):</span>
                        <span class="detail-value">${souscription.duree_mois || 'N/A'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Prix du logement:</span>
                        <span class="detail-value">${formatMontant(souscription.prix_logement)}</span>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 20px;">
                    <button class="modal-close-btn" onclick="closeModal()">Fermer</button>
                </div>
            `;
            
            modal.style.display = 'block';
        }
        
        function closeModal() {
            document.getElementById('subscriptionModal').style.display = 'none';
        }
        
        // Fermer le modal en cliquant en dehors
        window.onclick = function(event) {
            const modal = document.getElementById('subscriptionModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
@endsection
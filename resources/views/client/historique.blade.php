@extends('layouts.client')

@section('content')
<style>
    .historique-container {
        padding: 0;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Header Section */
    .historique-header {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.5rem;
    }

    .page-title i {
        color: #3b5998;
    }

    .page-subtitle {
        font-size: 1rem;
        color: #64748b;
    }

    /* Historique Table */
    .historique-section {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .table-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .table-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .historique-table {
        width: 100%;
        border-collapse: collapse;
    }

    .historique-table thead {
        background: #f8fafc;
    }

    .historique-table th {
        padding: 1.2rem 1.5rem;
        text-align: left;
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .historique-table td {
        padding: 1.2rem 1.5rem;
        border-top: 1px solid #f1f5f9;
        color: #1e293b;
    }

    .historique-table tbody tr {
        transition: background 0.2s ease;
    }

    .historique-table tbody tr:hover {
        background: #f8fafc;
    }

    .status-badge {
        padding: 0.4rem 0.9rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-badge.solde {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .status-badge.en-attente {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .status-badge.rejete {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1.5rem 2rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
    }

    .page-item {
        margin: 0;
    }

    .page-link {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        background: #f8fafc;
        color: #64748b;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
    }

    .page-link:hover {
        background: #3b5998;
        color: white;
        border-color: #3b5998;
    }

    .page-item.active .page-link {
        background: #3b5998;
        color: white;
        border-color: #3b5998;
    }

    .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        color: #94a3b8;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.5rem;
        }

        .table-responsive {
            overflow-x: scroll;
        }

        .historique-table th,
        .historique-table td {
            padding: 0.8rem 1rem;
            font-size: 0.85rem;
        }
    }
</style>

<div class="historique-container">
    <!-- Header -->
    <div class="historique-header">
        <h1 class="page-title">
            <i class="fas fa-history"></i>
            Historique de Paiement
        </h1>
        <p class="page-subtitle">Consultez l'historique complet de tous vos paiements</p>
    </div>

    <!-- Table Historique -->
    <div class="historique-section">
        <div class="table-header">
            <h2 class="table-title">Historique</h2>
        </div>

        @if($paiements->count() > 0)
            <div class="table-responsive">
                <table class="historique-table">
                    <thead>
                        <tr>
                            <th>Type de paiement</th>
                            <th>Montant total</th>
                            <th>Montant payé</th>
                            <th>Date du paiement</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paiements as $paiement)
                        <tr>
                            <td>
                                @if($paiement->type == 'frais_dossier')
                                    <strong>Frais de dossier</strong>
                                @elseif($paiement->type == 'apport_initial')
                                    <strong>Apport initial</strong>
                                @elseif($paiement->type == 'paiement_projet')
                                    <strong>Paiement projet</strong>
                                @else
                                    <strong>{{ ucfirst(str_replace('_', ' ', $paiement->type)) }}</strong>
                                @endif
                            </td>
                            <td><strong>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</strong></td>
                            <td><strong>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</strong></td>
                            <td>{{ $paiement->date_paiement ? $paiement->date_paiement->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                @if($paiement->statut == 'Soldé' || $paiement->statut == 'solde')
                                    <span class="status-badge solde">Soldé</span>
                                @elseif($paiement->statut == 'En attente' || $paiement->statut == 'en_attente')
                                    <span class="status-badge en-attente">En attente</span>
                                @else
                                    <span class="status-badge rejete">{{ $paiement->statut }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $paiements->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <h3>Aucun paiement trouvé</h3>
                <p>Vous n'avez pas encore effectué de paiement</p>
            </div>
        @endif
    </div>
</div>
@endsection

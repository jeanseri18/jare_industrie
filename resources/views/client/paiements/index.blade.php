@extends('layouts.client')

@section('content')
<style>
    .paiements-container {
        padding: 2rem 1.5rem;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.5rem 2rem;
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-title h1 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1e293b;
    }

    .page-title i {
        font-size: 1.8rem;
        color: #3b5998;
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.8rem 1.5rem;
        background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(59, 89, 152, 0.3);
        position: relative;
        overflow: hidden;
    }

    .btn-add::before {
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

    .btn-add:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 89, 152, 0.4);
    }

    /* Alert Messages */
    .alert {
        padding: 1rem 1.2rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        animation: slideDown 0.4s ease-out;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 2px solid rgba(16, 185, 129, 0.3);
        color: #059669;
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        border: 2px solid rgba(239, 68, 68, 0.3);
        color: #dc2626;
    }

    .alert i {
        font-size: 1.3rem;
    }

    /* Table Styling */
    .table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out 0.2s backwards;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .custom-table th {
        padding: 1.2rem 1.5rem;
        text-align: left;
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e2e8f0;
    }

    .custom-table td {
        padding: 1.2rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        color: #1e293b;
    }

    .custom-table tbody tr {
        transition: all 0.3s ease;
    }

    .custom-table tbody tr:hover {
        background: rgba(59, 89, 152, 0.02);
        transform: translateY(-1px);
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .status-badge.status-paye {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border: 2px solid rgba(16, 185, 129, 0.3);
    }

    .status-badge.status-en_attente {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
        border: 2px solid rgba(245, 158, 11, 0.3);
    }

    .status-badge.status-annule {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border: 2px solid rgba(239, 68, 68, 0.3);
    }

    .status-badge.status-rembourse {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
        border: 2px solid rgba(59, 130, 246, 0.3);
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .btn-action.btn-details {
        background: rgba(59, 89, 152, 0.1);
        color: #3b5998;
        border-color: rgba(59, 89, 152, 0.2);
    }

    .btn-action.btn-details:hover {
        background: #3b5998;
        color: white;
    }

    .btn-action.btn-receipt {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border-color: rgba(16, 185, 129, 0.2);
    }

    .btn-action.btn-receipt:hover {
        background: #059669;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #64748b;
    }

    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .pagination {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }
</style>

<div class="paiements-container">
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-credit-card"></i>
            <h1>{{ __('Mes Paiements') }}</h1>
        </div>
        <a href="{{ route('client.paiements.create') }}" class="btn-add">
            <i class="fas fa-plus"></i>
            {{ __('Effectuer un paiement') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

                <div class="table-container">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Numéro</th>
                                <th>Type</th>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paiements as $paiement)
                                <tr>
                                    <td>
                                        {{ $paiement->numero_paiement }}
                                    </td>
                                    <td>
                                        {{ ucfirst(str_replace('_', ' ', $paiement->type)) }}
                                    </td>
                                    <td>
                                        {{ number_format($paiement->montant, 2, ',', ' ') }} F CFA
                                    </td>
                                    <td>
                                        {{ $paiement->date_paiement ? $paiement->date_paiement->format('d/m/Y') : 'Non payé' }}
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $paiement->statut }}">
                                            {{ ucfirst($paiement->statut) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('client.paiements.show', $paiement) }}" class="btn-action btn-details">
                                                Détails
                                            </a>
                                            @if($paiement->recu_paiement)
                                                <a href="{{ asset('storage/' . $paiement->recu_paiement) }}" target="_blank" class="btn-action btn-receipt">
                                                    Reçu
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <i class="fas fa-credit-card"></i>
                                        <h3>Aucun paiement trouvé</h3>
                                        <p>Commencez par effectuer votre premier paiement</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($paiements->hasPages())
                    <div class="pagination">
                        {{ $paiements->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
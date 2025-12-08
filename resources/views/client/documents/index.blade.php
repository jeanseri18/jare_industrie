@extends('layouts.client')

@section('content')
<style>
    .documents-container {
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

    /* Stats Overview */
    .stats-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.2rem;
        margin-bottom: 2rem;
        animation: fadeInUp 0.6s ease-out 0.2s backwards;
    }

    .stat-box {
        background: white;
        border-radius: 12px;
        padding: 1.3rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .stat-icon.total {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .stat-icon.valid {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .stat-icon.pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .stat-icon.rejected {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .stat-content h4 {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0.3rem;
    }

    .stat-content .value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1e293b;
    }

    /* Documents Grid */
    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        animation: fadeInUp 0.6s ease-out 0.3s backwards;
    }

    .document-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        animation: fadeInUp 0.6s ease-out backwards;
    }

    .document-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        border-color: #3b5998;
    }

    .document-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }

    .document-type {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .type-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .type-label {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.95rem;
    }

    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-badge.valid {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .status-badge.pending {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .status-badge.rejected {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .document-body {
        margin-bottom: 1rem;
    }

    .file-name {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 0.8rem;
    }

    .file-name i {
        color: #3b5998;
    }

    .validation-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #94a3b8;
        font-size: 0.85rem;
    }

    .document-actions {
        display: flex;
        gap: 0.8rem;
        padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
    }

    .btn-action {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-view {
        background: rgba(59, 89, 152, 0.1);
        color: #3b5998;
    }

    .btn-view:hover {
        background: #3b5998;
        color: white;
        transform: translateY(-2px);
    }

    .btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .btn-delete:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        animation: fadeInUp 0.6s ease-out 0.3s backwards;
    }

    .empty-icon {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1.5rem;
    }

    .empty-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .empty-subtitle {
        color: #64748b;
        margin-bottom: 2rem;
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .documents-container {
            padding: 1rem;
        }

        .page-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .page-title h1 {
            font-size: 1.5rem;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }

        .documents-grid {
            grid-template-columns: 1fr;
        }

        .stats-overview {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="documents-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-folder-open"></i>
            <h1>Mes Documents</h1>
        </div>
        <a href="{{ route('client.documents.create') }}" class="btn-add">
            <i class="fas fa-plus-circle"></i>
            <span style="position: relative; z-index: 1;">Ajouter un document</span>
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Stats Overview -->
    <div class="stats-overview">
        <div class="stat-box">
            <div class="stat-icon total">
                <i class="fas fa-file"></i>
            </div>
            <div class="stat-content">
                <h4>Total</h4>
                <div class="value">{{ $documents->total() }}</div>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-icon valid">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h4>Validés</h4>
                <div class="value">{{ $documents->where('statut', 'valide')->count() }}</div>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-icon pending">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h4>En attente</h4>
                <div class="value">{{ $documents->where('statut', 'en_attente')->count() }}</div>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-icon rejected">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-content">
                <h4>Rejetés</h4>
                <div class="value">{{ $documents->where('statut', 'rejete')->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Documents Grid -->
    @if($documents->count() > 0)
        <div class="documents-grid">
            @foreach($documents as $document)
                <div class="document-card">
                    <div class="document-header">
                        <div class="document-type">
                            <div class="type-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="type-label">
                                {{ ucfirst(str_replace('_', ' ', $document->type_document)) }}
                            </div>
                        </div>
                        <span class="status-badge 
                            @if($document->statut == 'valide') valid
                            @elseif($document->statut == 'rejete') rejected
                            @else pending
                            @endif">
                            @if($document->statut == 'valide')
                                <i class="fas fa-check"></i> Validé
                            @elseif($document->statut == 'rejete')
                                <i class="fas fa-times"></i> Rejeté
                            @else
                                <i class="fas fa-clock"></i> En attente
                            @endif
                        </span>
                    </div>

                    <div class="document-body">
                        <div class="file-name">
                            <i class="fas fa-paperclip"></i>
                            <span>{{ Str::limit($document->nom_fichier, 30) }}</span>
                        </div>
                        <div class="validation-date">
                            <i class="fas fa-calendar"></i>
                            <span>
                                @if($document->date_validation)
                                    Validé le {{ $document->date_validation->format('d/m/Y') }}
                                @else
                                    Non validé
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="document-actions">
                        <a href="{{ $document->url() }}" target="_blank" class="btn-action btn-view">
                            <i class="fas fa-eye"></i>
                            <span>Voir</span>
                        </a>
                        @if($document->statut == 'en_attente')
                            <form action="{{ route('client.documents.destroy', $document) }}" method="POST" style="flex: 1;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" style="width: 100%;" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document?')">
                                    <i class="fas fa-trash"></i>
                                    <span>Supprimer</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-folder-open"></i>
            </div>
            <h3 class="empty-title">Aucun document</h3>
            <p class="empty-subtitle">Vous n'avez pas encore ajouté de documents</p>
            <a href="{{ route('client.documents.create') }}" class="btn-add">
                <i class="fas fa-plus-circle"></i>
                <span style="position: relative; z-index: 1;">Ajouter votre premier document</span>
            </a>
        </div>
    @endif

    <!-- Pagination -->
    @if($documents->hasPages())
        <div class="pagination-wrapper">
            {{ $documents->links() }}
        </div>
    @endif
</div>
@endsection
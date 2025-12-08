@extends('layouts.client')

@section('content')
<style>
    .notifications-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .page-header {
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        animation: fadeInDown 0.6s ease-out;
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        position: relative;
    }
    
    .page-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        border-radius: 2px;
    }
    
    .btn-gradient {
        background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }
    
    .notification-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        animation: fadeInUp 0.5s ease-out;
        animation-fill-mode: both;
    }
    
    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    .notification-card.unread {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-color: #0ea5e9;
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.15);
    }
    
    .notification-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .notification-title.unread {
        color: #1e40af;
    }
    
    .notification-title.read {
        color: #374151;
    }
    
    .notification-message {
        color: #6b7280;
        margin-bottom: 0.75rem;
        line-height: 1.5;
    }
    
    .notification-time {
        font-size: 0.875rem;
        color: #9ca3af;
        margin-bottom: 1rem;
    }
    
    .notification-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }
    
    .btn-link {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }
    
    .btn-link:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }
    
    .btn-mark-read {
        background: transparent;
        color: #6b7280;
        border: 1px solid #d1d5db;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-mark-read:hover {
        background: #f9fafb;
        color: #374151;
        border-color: #9ca3af;
    }
    
    .badge-new {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        animation: pulse 2s infinite;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6b7280;
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border: 1px solid #34d399;
        color: #065f46;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        animation: slideInDown 0.5s ease-out;
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.8;
        }
    }
    
    .pagination {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }
    
    .pagination .page-link {
        color: #3b82f6;
        border: 1px solid #d1d5db;
        padding: 0.5rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .pagination .page-link:hover {
        background: #f3f4f6;
        border-color: #3b82f6;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border-color: #3b82f6;
    }
</style>

<div class="notifications-container">
    <div class="page-header">
        <h1 class="page-title">{{ __('Mes Notifications') }}</h1>
        <form action="{{ route('client.notifications.markAllAsRead') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn-gradient">
                {{ __('Tout marquer comme lu') }}
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="notifications-list">
                    @forelse($notifications as $index => $notification)
                        <div class="notification-card @if(!$notification->lu) unread @endif" style="animation-delay: {{ $index * 0.1 }}s">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="notification-title @if(!$notification->lu) unread @else read @endif">
                                        {{ $notification->titre }}
                                    </h3>
                                    <p class="notification-message">
                                        {{ $notification->message }}
                                    </p>
                                    <p class="notification-time">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    @if(!$notification->lu)
                                        <span class="badge-new">
                                            Nouveau
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="notification-actions">
                                <div>
                                    @if($notification->type == 'document')
                                        <a href="{{ route('client.documents') }}" class="btn-link">
                                            Voir les documents
                                        </a>
                                    @elseif($notification->type == 'paiement')
                                        <a href="{{ route('client.paiements') }}" class="btn-link">
                                            Voir les paiements
                                        </a>
                                    @elseif($notification->type == 'souscription')
                                        <a href="{{ route('client.dashboard') }}" class="btn-link">
                                            Voir les souscriptions
                                        </a>
                                    @endif
                                </div>
                                <div>
                                    @if(!$notification->lu)
                                        <form action="{{ route('client.notifications.markAsRead', $notification) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="btn-mark-read">
                                                Marquer comme lu
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">🔔</div>
                            <h3>Aucune notification</h3>
                            <p>Vous n'avez aucune notification pour le moment.</p>
                        </div>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    <div class="pagination">
                        {{ $notifications->links() }}
                    </div>
                @endif
</div>
@endsection
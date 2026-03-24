@extends('layouts.client')

@section('content')
<style>
    .notifications-container {
        padding: 0;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Header Section */
    .notifications-header {
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

    /* Notifications Section */
    .notifications-section {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .section-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
    }

    /* Notification Item */
    .notification-item {
        padding: 1.8rem 2rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        gap: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item:hover {
        background: #f8fafc;
        transform: translateX(5px);
    }

    .notification-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .notification-icon.blue {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
    }

    .notification-icon.orange {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .notification-icon.red {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .notification-icon.green {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .notification-content {
        flex: 1;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 0.8rem;
        gap: 1rem;
    }

    .notification-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.3rem;
    }

    .notification-date {
        font-size: 0.85rem;
        color: #94a3b8;
        white-space: nowrap;
    }

    .notification-message {
        font-size: 0.95rem;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 0.5rem;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #94a3b8;
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

        .notification-item {
            padding: 1.5rem;
            flex-direction: column;
            gap: 1rem;
        }

        .notification-header {
            flex-direction: column;
            gap: 0.5rem;
        }

        .notification-date {
            text-align: left;
        }
    }
</style>

<div class="notifications-container">
    <!-- Header -->
    <div class="notifications-header">
        <h1 class="page-title">
            <i class="fas fa-bell"></i>
            Notifications
        </h1>
        <p class="page-subtitle">Restez informé de toutes vos activités et mises à jour</p>
    </div>

    <!-- Notifications List -->
    <div class="notifications-section">
        <div class="section-header">
            <h2 class="section-title">Notifications</h2>
        </div>

        @if($notifications->count() > 0)
            @foreach($notifications as $notification)
            <div class="notification-item">
                <div class="notification-icon {{ $notification->color }}">
                    <i class="{{ $notification->icon }}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-header">
                        <div>
                            <h3 class="notification-title">{{ $notification->titre }}</h3>
                        </div>
                        <span class="notification-date">{{ $notification->relative }}</span>
                    </div>
                    <p class="notification-message">{{ $notification->message }}</p>
                    <div class="notification-meta">
                        <i class="fas fa-calendar"></i>
                        <span>{{ $notification->date }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <h3>Aucune notification</h3>
                <p>Vous n'avez pas encore de notifications</p>
            </div>
        @endif
    </div>
</div>
@endsection

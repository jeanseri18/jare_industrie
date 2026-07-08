@extends('layouts.client')

@section('title', 'Notifications')

@section('content')
<x-page-header title="Notifications" subtitle="Restez informé de toutes vos activités et mises à jour" />
<x-alert />

<x-data-table>
    <x-slot:head>
        <tr>
            <th>Notification</th>
            <th>Message</th>
            <th>Date</th>
        </tr>
    </x-slot:head>
    @forelse($notifications as $notification)
        <tr>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <span class="stat-icon icon-{{ $notification->color === 'red' ? 'red' : 'gray' }}" style="width:36px;height:36px;font-size:14px;">
                        <i class="{{ $notification->icon }}"></i>
                    </span>
                    <strong>{{ $notification->titre }}</strong>
                </div>
            </td>
            <td class="text-slate-600">{{ $notification->message }}</td>
            <td>
                <div>{{ $notification->date }}</div>
                <div class="small text-muted">{{ $notification->relative }}</div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="3">
                <x-empty-state title="Aucune notification" message="Vous n'avez pas encore de notifications." />
            </td>
        </tr>
    @endforelse
</x-data-table>
@endsection

@extends('layouts.dg')

@section('title', 'Suivi des équipes / Actions de l\'utilisateur')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            Actions de l'utilisateur: {{ $user->name }}
        </div>
        <div>
            <a href="{{ route('dg.equipes.index') }}" class="btn btn-secondary btn-sm">Retour à la liste</a>
        </div>
    </div>

    <div style="padding: 20px;">
        <div class="mb-3">
            <div class="text-muted">Email: {{ $user->email }} • Rôle: {{ $user->role }}</div>
        </div>

        @if($activities->count())
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Modèle</th>
                            <th>IP</th>
                            <th>Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $log)
                            <tr>
                                <td>{{ $log->created_at?->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge-custom badge-{{ $log->action_class ?? 'primary' }}">{{ $log->action_label ?? ucfirst($log->action) }}</span>
                                </td>
                                <td>{{ $log->description }}</td>
                                <td>
                                    {{ class_basename($log->model_type) ?? '-' }}
                                    @if($log->model_id)
                                        #{{ $log->model_id }}
                                    @endif
                                </td>
                                <td>{{ $log->ip_address ?? '-' }}</td>
                                <td style="max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $log->user_agent ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <x-pagination :paginator="$activities" />
            </div>
        @else
            <div class="alert alert-info">Aucune action enregistrée pour cet utilisateur.</div>
        @endif
    </div>
</div>
@endsection

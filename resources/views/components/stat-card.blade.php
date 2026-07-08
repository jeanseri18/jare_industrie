@props([
    'label',
    'value',
    'icon' => null,
    'iconTone' => null,
    'color' => 'black',
    'footer' => null,
])

@php
    $tone = $iconTone ?? $color ?? 'blue';

    $iconDefaults = [
        'total clients' => ['fas fa-users', 'black'],
        'total projets' => ['fas fa-building', 'gray'],
        'mutuelles' => ['fas fa-handshake', 'gray'],
        'projets actifs' => ['fas fa-chart-line', 'gray'],
        'souscriptions en attente' => ['fas fa-clock', 'gray'],
        'en attente' => ['fas fa-clock', 'gray'],
        'souscriptions en cours' => ['fas fa-spinner', 'black'],
        'en cours' => ['fas fa-hourglass-half', 'black'],
        'souscriptions soldées' => ['fas fa-check-circle', 'gray'],
        'soldées' => ['fas fa-check-circle', 'gray'],
        'paiements enregistrés' => ['fas fa-receipt', 'black'],
        'montant encaissé' => ['fas fa-money-bill-wave', 'gray'],
        'montant total' => ['fas fa-coins', 'black'],
        'montant total dû' => ['fas fa-coins', 'black'],
        'montant payé' => ['fas fa-circle-check', 'gray'],
        'montant restant' => ['fas fa-hourglass-half', 'red'],
        'dossiers annulés' => ['fas fa-ban', 'red'],
        'montant remboursé' => ['fas fa-rotate-left', 'gray'],
        'total à corriger' => ['fas fa-triangle-exclamation', 'red'],
        'total corrigées' => ['fas fa-check', 'gray'],
        'total paiements soldés' => ['fas fa-trophy', 'gray'],
        'moyenne par projet' => ['fas fa-chart-line', 'gray'],
        'ce mois' => ['fas fa-calendar', 'gray'],
        'souscriptions' => ['fas fa-file-contract', 'black'],
        'salaire mensuel' => ['fas fa-money-bill-wave', 'gray'],
        'enfants' => ['fas fa-child', 'gray'],
        'statut' => ['fas fa-user', 'black'],
        'progression' => ['fas fa-chart-line', 'gray'],
        'souscriptions corrigées' => ['fas fa-check', 'gray'],
        'taux d\'erreurs opérateurs' => ['fas fa-xmark', 'red'],
        'total dossiers soumis' => ['fas fa-file-circle-plus', 'black'],
        'total à corriger' => ['fas fa-triangle-exclamation', 'red'],
    ];

    $labelKey = strtolower(trim($label));
    if (!$icon && isset($iconDefaults[$labelKey])) {
        [$icon, $tone] = $iconDefaults[$labelKey];
    }

    $icon ??= 'fas fa-chart-bar';
@endphp

<div {{ $attributes->merge(['class' => 'stat-card']) }}>
    <div class="stat-header">
        <span class="stat-title">{{ $label }}</span>
        <div class="stat-icon icon-{{ $tone }}">
            <i class="{{ $icon }}"></i>
        </div>
    </div>
    <div class="stat-value">{{ $value }}</div>
    @if($footer)
        <div class="stat-footer">{{ $footer }}</div>
    @endif
</div>

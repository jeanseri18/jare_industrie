@props(['status'])

@php
    $map = [
        'payé' => 'badge-success', 'sold' => 'badge-success', 'SOLD' => 'badge-success',
        'en_attente' => 'badge-warning', 'annulee' => 'badge-danger',
        'FRAIS_OK' => 'badge-info', 'APPORT_OK' => 'badge-info',
    ];
    $class = $map[strtolower($status)] ?? 'badge-secondary';
@endphp
<span {{ $attributes->merge(['class' => "badge {$class}"]) }}>{{ $status }}</span>

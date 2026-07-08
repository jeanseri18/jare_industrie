@props(['class' => 'max-w-[200px] h-auto object-contain'])

@php
    $branding = $branding ?? \App\Support\CurrentOrganization::branding();
@endphp

@if($branding?->logo_path && $branding->logoUrl())
    <img src="{{ $branding->logoUrl() }}" alt="{{ $branding->displayName() }}" {{ $attributes->merge(['class' => $class]) }}>
@endif

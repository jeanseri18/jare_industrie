@props(['user', 'size' => 'md'])

@php
    $sizeClass = match ($size) {
        'sm' => 'app-user-avatar-sm',
        'lg' => 'app-user-avatar-lg',
        default => '',
    };
    $photoUrl = $user?->profilePhotoUrl();
@endphp

@if($photoUrl)
    <span {{ $attributes->merge(['class' => trim("app-user-avatar $sizeClass")]) }}>
        <img src="{{ $photoUrl }}" alt="{{ $user->initials() }}">
    </span>
@else
    <span
        {{ $attributes->merge(['class' => trim("app-user-avatar $sizeClass")]) }}
        aria-hidden="true"
    >
        {{ $user?->initials() }}
    </span>
@endif

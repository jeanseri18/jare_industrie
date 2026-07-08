@props([
    'title' => null,
    'collapsible' => false,
    'open' => true,
    'id' => null,
    'icon' => null,
])

@php
    $sectionId = $id ?? 'detail-' . \Illuminate\Support\Str::slug($title ?? 'section');
@endphp

<div {{ $attributes->merge(['class' => 'data-table-container mb-4']) }}
    @if($collapsible && $title) x-data="{ open: @json($open) }" @endif>
    @if($collapsible && $title)
        <div class="detail-section__header card-header-toggle d-flex justify-content-between align-items-center"
             @click="open = !open"
             role="button"
             :aria-expanded="open ? 'true' : 'false'"
             aria-controls="{{ $sectionId }}">
            <h3 class="detail-section__title mb-0">
                @if($icon)<i class="{{ $icon }} me-2"></i>@endif
                {{ $title }}
            </h3>
            <i class="fas fa-chevron-down toggle-icon" :class="{ 'toggle-icon--closed': !open }"></i>
        </div>
        <div id="{{ $sectionId }}" class="detail-section__panel" x-show="open">
            <div class="list-body">{{ $slot }}</div>
        </div>
    @else
        @if($title)
            <div class="detail-section__header detail-section__header--static">
                <h3 class="detail-section__title mb-0">
                    @if($icon)<i class="{{ $icon }} me-2"></i>@endif
                    {{ $title }}
                </h3>
            </div>
        @endif
        <div class="list-body">{{ $slot }}</div>
    @endif
</div>

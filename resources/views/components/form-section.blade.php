@props(['title'])

<div {{ $attributes->merge(['class' => 'space-y-4']) }}>
    @if($title)
        <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">{{ $title }}</h3>
    @endif
    {{ $slot }}
</div>

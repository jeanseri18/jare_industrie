@props(['title', 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between']) }}>
    <div>
        <h2 class="text-2xl font-bold text-slate-900">{{ $title }}</h2>
        @if($subtitle)
            <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
        @endif
    </div>
    @if(isset($actions))
        <div class="flex flex-wrap gap-2">{{ $actions }}</div>
    @endif
</div>

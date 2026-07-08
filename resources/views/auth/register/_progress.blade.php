@props(['step' => 1])

@php
    $steps = [
        1 => 'Votre compte',
        2 => 'Votre entreprise',
        3 => 'Identité visuelle',
    ];
@endphp

<ol class="mb-8 flex flex-wrap gap-2">
    @foreach($steps as $num => $label)
        <li class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium {{ $step === $num ? 'bg-[#ff7200] text-white' : ($step > $num ? 'bg-emerald-50 text-emerald-800' : 'bg-slate-100 text-slate-500') }}">
            <span class="flex h-6 w-6 items-center justify-center rounded-full text-xs {{ $step === $num ? 'bg-white/20' : ($step > $num ? 'bg-emerald-200' : 'bg-white') }}">{{ $num }}</span>
            {{ $label }}
        </li>
    @endforeach
</ol>
